<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Zobrazí seznam všech úkolů.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Výchozí řazení podle termínu
        $orderBy = $request->query('order_by', 'due_date');
        $direction = $request->query('direction', 'asc');

        // Filtrování podle stavu
        $status = $request->query('status');

        // Filtrování podle kategorie
        $categoryId = $request->query('category_id');

        // Filtrování podle priority
        $priority = $request->query('priority');

        // Základní query
        $query = Task::where('user_id', $user->id);

        // Aplikujeme filtry, pokud jsou zadány
        if ($status) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        // Textové vyhledávání
        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Řazení
        $query->orderBy($orderBy, $direction);

        // Stránkování
        $tasks = $query->paginate(10)->withQueryString();

        // Získání kategorií pro filtr
        $categories = Category::where('user_id', $user->id)->get();

        return view('tasks.index', compact('tasks', 'categories', 'status', 'categoryId', 'priority', 'orderBy', 'direction'));
    }

    /**
     * Zobrazí formulář pro vytvoření nového úkolu.
     */
    public function create()
    {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->get();

        return view('tasks.create', compact('categories'));
    }

    /**
     * Uloží nově vytvořený úkol.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|in:1,2,3',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'points_value' => 'nullable|integer|min:1',
            'recurring' => 'nullable|boolean',
            'recurring_type' => 'nullable|required_if:recurring,1|in:daily,weekly,monthly',
            'recurring_interval' => 'nullable|required_if:recurring,1|integer|min:1',
            'assign_today' => 'nullable|boolean',
        ]);

        // Přidání user_id
        $validated['user_id'] = Auth::id();

        // Převod due_date na správný formát, pokud je zadáno
        if (isset($validated['due_date']) && $validated['due_date']) {
            $validated['due_date'] = Carbon::parse($validated['due_date']);
        }

        // Převod recurring na boolean
        $validated['recurring'] = isset($validated['recurring']) && $validated['recurring'] ? true : false;

        // Pokud je úkol opakující se, nastavíme ho jako rodičovský
        if ($validated['recurring']) {
            $validated['is_recurring_parent'] = true;
        }

        // Vytvoření úkolu
        $task = Task::create($validated);

        // Pokud máme assign_today nebo je due_date dnes, přiřadíme úkol na dnešek
        $assignToday = isset($validated['assign_today']) && $validated['assign_today'];
        $isDueToday = isset($validated['due_date']) && Carbon::parse($validated['due_date'])->isToday();

        if ($assignToday || $isDueToday) {
            $task->assignToToday();
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Úkol byl úspěšně vytvořen!');
    }

    /**
     * Zobrazí detail úkolu.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Zobrazí formulář pro editaci úkolu.
     */
    public function edit(Task $task)
    {

        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)->get();

        return view('tasks.edit', compact('task', 'categories'));
    }

    /**
     * Aktualizuje úkol.
     */
    public function update(Request $request, Task $task)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|in:1,2,3',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'points_value' => 'nullable|integer|min:1',
            'recurring' => 'nullable|boolean',
            'recurring_type' => 'nullable|required_if:recurring,1|in:daily,weekly,monthly',
            'recurring_interval' => 'nullable|required_if:recurring,1|integer|min:1',
            'assign_today' => 'nullable|boolean',
        ]);

        // Převod due_date na správný formát, pokud je zadáno
        if (isset($validated['due_date']) && $validated['due_date']) {
            $validated['due_date'] = Carbon::parse($validated['due_date']);
        }

        // Převod recurring na boolean
        $validated['recurring'] = isset($validated['recurring']) && $validated['recurring'] ? true : false;

        // Aktualizace úkolu
        $task->update($validated);

        // Pokud máme assign_today, přiřadíme úkol na dnešek (pokud už není)
        if (isset($validated['assign_today']) && $validated['assign_today'] && !$task->isAssignedToday()) {
            $task->assignToToday();
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Úkol byl úspěšně aktualizován!');
    }

    /**
     * Smaže úkol.
     */
    public function destroy(Task $task)
    {

        // Pokud je to rodičovský opakující se úkol, ověříme, zda chceme smazat i dceřiné úkoly
        if ($task->is_recurring_parent && $task->childTasks()->count() > 0) {
            return redirect()->route('tasks.confirm-delete', $task);
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Úkol byl úspěšně smazán!');
    }

    /**
     * Zobrazí potvrzení pro smazání opakujícího se úkolu.
     */
    public function confirmDelete(Task $task)
    {

        if (!$task->is_recurring_parent || $task->childTasks()->count() === 0) {
            return redirect()->route('tasks.destroy', $task);
        }

        return view('tasks.confirm-delete', compact('task'));
    }

    /**
     * Smaže opakující se úkol a případně i jeho instance.
     */
    public function deleteRecurring(Request $request, Task $task)
    {

        $deleteChildren = $request->input('delete_children', false);

        if ($deleteChildren) {
            // Smazat všechny dceřiné úkoly
            $task->childTasks()->delete();
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Úkol byl úspěšně smazán!');
    }

    /**
     * Označí úkol jako splněný.
     */
    public function complete(Task $task)
    {

        $task->complete();

        return redirect()->back()
            ->with('success', 'Úkol byl označen jako splněný! Získali jste ' . $task->points_value . ' bodů.');
    }

    /**
     * Označí úkol jako nesplněný.
     */
    public function fail(Task $task)
    {

        $task->fail();

        $penalty = $task->points_value * 2;

        return redirect()->back()
            ->with('error', 'Úkol byl označen jako nesplněný. Ztratili jste ' . $penalty . ' bodů.');
    }

    /**
     * Přiřadí úkol na dnešní den.
     */
    public function assignToday(Task $task)
    {

        if ($task->isAssignedToday()) {
            return redirect()->back()
                ->with('info', 'Úkol je již přiřazen na dnešní den.');
        }

        $task->assignToToday();

        return redirect()->back()
            ->with('success', 'Úkol byl přiřazen na dnešní den.');
    }
}
