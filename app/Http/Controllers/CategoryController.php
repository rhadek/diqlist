<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Zobrazí seznam všech kategorií.
     */
    public function index()
    {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->id)
            ->withCount(['tasks', 'tasks as pending_tasks_count' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->withCount(['tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Zobrazí formulář pro vytvoření nové kategorie.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Uloží nově vytvořenou kategorii.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color_code' => 'nullable|string|regex:/^#[a-f0-9]{6}$/i', // Validace HEX barvy
            'description' => 'nullable|string',
        ]);

        // Přidání user_id
        $validated['user_id'] = Auth::id();

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategorie byla úspěšně vytvořena!');
    }

    /**
     * Zobrazí detail kategorie včetně úkolů v ní.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        // Načtení úkolů v kategorii
        $pendingTasks = $category->tasks()->where('status', 'pending')->get();
        $completedTasks = $category->tasks()->where('status', 'completed')->get();

        return view('categories.show', compact('category', 'pendingTasks', 'completedTasks'));
    }

    /**
     * Zobrazí formulář pro editaci kategorie.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    /**
     * Aktualizuje kategorii.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color_code' => 'nullable|string|regex:/^#[a-f0-9]{6}$/i', // Validace HEX barvy
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategorie byla úspěšně aktualizována!');
    }

    /**
     * Smaže kategorii.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        // Zjistíme, zda má kategorie nějaké úkoly
        $tasksCount = $category->tasks()->count();

        if ($tasksCount > 0) {
            return redirect()->route('categories.confirm-delete', $category);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategorie byla úspěšně smazána!');
    }

    /**
     * Zobrazí potvrzení pro smazání kategorie s úkoly.
     */
    public function confirmDelete(Category $category)
    {
        $this->authorize('delete', $category);

        $tasksCount = $category->tasks()->count();

        if ($tasksCount === 0) {
            return redirect()->route('categories.destroy', $category);
        }

        return view('categories.confirm-delete', compact('category', 'tasksCount'));
    }

    /**
     * Smaže kategorii a případně její úkoly nebo je přeřadí.
     */
    public function deleteCategoryWithTasks(Request $request, Category $category)
    {
        $this->authorize('delete', $category);

        $action = $request->input('action', 'reassign');

        if ($action === 'delete') {
            // Smazat kategorii i s úkoly
            $category->tasks()->delete();
            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', 'Kategorie a všechny její úkoly byly úspěšně smazány!');
        } else {
            // Odstranit vazbu na kategorii (nastavit null)
            $category->tasks()->update(['category_id' => null]);
            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', 'Kategorie byla smazána a úkoly byly přesunuty do nezařazených.');
        }
    }
}
