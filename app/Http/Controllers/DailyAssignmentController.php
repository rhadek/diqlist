<?php

namespace App\Http\Controllers;

use App\Models\DailyAssignment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyAssignmentController extends Controller
{
    /**
     * Zobrazí seznam přiřazení úkolů pro vybraný den (výchozí dnes).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Získáme datum z requestu nebo použijeme dnešek
        $date = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::today();

        // Získáme všechna přiřazení pro daný den
        $assignments = DailyAssignment::where('user_id', $user->id)
            ->where('date', $date->toDateString())
            ->with('task')
            ->get();

        // Rozdělíme na čekající, dokončené a nesplněné
        $pendingAssignments = $assignments->where('status', 'pending');
        $completedAssignments = $assignments->where('status', 'completed');
        $failedAssignments = $assignments->where('status', 'failed');

        // Získáme počet bodů za den
        $pointsToday = $user->pointTransactions()
            ->whereDate('created_at', $date)
            ->sum('points_change');

        // Získáme dostupné úkoly pro přiřazení (ty, které ještě nejsou přiřazeny na vybraný den)
        $availableTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDoesntHave('dailyAssignments', function ($query) use ($date) {
                $query->where('date', $date->toDateString());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('due_date')
            ->get();

        // Formátujeme datum
        $formattedDate = $date->translatedFormat('l j. F Y');

        // Vytvoříme navigaci pro předchozí a následující den
        $previousDay = $date->copy()->subDay();
        $nextDay = $date->copy()->addDay();

        return view('daily.index', compact(
            'date',
            'formattedDate',
            'pendingAssignments',
            'completedAssignments',
            'failedAssignments',
            'pointsToday',
            'availableTasks',
            'previousDay',
            'nextDay'
        ));
    }

    /**
     * Přiřadí úkol k vybranému dni.
     */
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'date' => 'required|date',
            'is_bonus' => 'nullable|boolean',
        ]);

        // Zkontrolujeme, zda je to uživatelův úkol
        $task = Task::findOrFail($validated['task_id']);
        $this->authorize('update', $task);

        // Zkontrolujeme, zda již není úkol přiřazen k danému dni
        $existingAssignment = DailyAssignment::where('task_id', $task->id)
            ->where('date', $validated['date'])
            ->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'Úkol je již přiřazen k tomuto dni.');
        }

        // Vytvoříme nové přiřazení
        $assignment = new DailyAssignment();
        $assignment->user_id = Auth::id();
        $assignment->task_id = $task->id;
        $assignment->date = $validated['date'];
        $assignment->status = 'pending';
        $assignment->is_bonus = $validated['is_bonus'] ?? false;
        $assignment->save();

        return redirect()->back()
            ->with('success', 'Úkol byl úspěšně přiřazen na vybraný den.');
    }

    /**
     * Odebere přiřazení úkolu z vybraného dne.
     */
    public function unassign(DailyAssignment $assignment)
    {
        $this->authorize('delete', $assignment);

        // Úkol je možné odebrat pouze pokud je ve stavu 'pending'
        if ($assignment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Nelze odebrat splněný nebo nesplněný úkol.');
        }

        $assignment->delete();

        return redirect()->back()
            ->with('success', 'Úkol byl úspěšně odebrán z vybraného dne.');
    }

    /**
     * Označí přiřazený úkol jako splněný.
     */
    public function complete(DailyAssignment $assignment)
    {
        $this->authorize('update', $assignment);

        // Úkol je možné označit jako splněný pouze pokud je ve stavu 'pending'
        if ($assignment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Nelze označit jako splněný již splněný nebo nesplněný úkol.');
        }

        $assignment->complete();

        return redirect()->back()
            ->with('success', 'Úkol byl označen jako splněný! Získali jste ' . $assignment->task->points_value . ' bodů.');
    }

    /**
     * Označí přiřazený úkol jako nesplněný.
     */
    public function fail(DailyAssignment $assignment)
    {
        $this->authorize('update', $assignment);

        // Úkol je možné označit jako nesplněný pouze pokud je ve stavu 'pending'
        if ($assignment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Nelze označit jako nesplněný již splněný nebo nesplněný úkol.');
        }

        $assignment->fail();

        $penalty = $assignment->task->points_value * 2;

        return redirect()->back()
            ->with('error', 'Úkol byl označen jako nesplněný. Ztratili jste ' . $penalty . ' bodů.');
    }

    /**
     * Zobrazí přehled (kalendář) přiřazení.
     */
    public function calendar(Request $request)
    {
        $user = Auth::user();

        // Získáme měsíc a rok z requestu nebo použijeme aktuální
        $month = $request->query('month') ?: Carbon::now()->month;
        $year = $request->query('year') ?: Carbon::now()->year;

        // Vytvoříme první a poslední den měsíce
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Získáme všechna přiřazení pro daný měsíc
        $assignments = DailyAssignment::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->with('task')
            ->get()
            ->groupBy(function($assignment) {
                return Carbon::parse($assignment->date)->day;
            });

        // Spočítáme statistiky pro každý den
        $dailyStats = [];

        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $dayDate = Carbon::createFromDate($year, $month, $day);

            if (isset($assignments[$day])) {
                $dayAssignments = $assignments[$day];
                $completed = $dayAssignments->where('status', 'completed')->count();
                $total = $dayAssignments->count();
                $completion = $total > 0 ? round(($completed / $total) * 100) : 0;

                $dailyStats[$day] = [
                    'date' => $dayDate,
                    'assignments' => $dayAssignments,
                    'completed' => $completed,
                    'total' => $total,
                    'completion' => $completion
                ];
            } else {
                $dailyStats[$day] = [
                    'date' => $dayDate,
                    'assignments' => collect(),
                    'completed' => 0,
                    'total' => 0,
                    'completion' => 0
                ];
            }
        }

        // Vytvoříme navigaci pro předchozí a následující měsíc
        $previousMonth = Carbon::createFromDate($year, $month, 1)->subMonth();
        $nextMonth = Carbon::createFromDate($year, $month, 1)->addMonth();

        // Formátujeme název měsíce
        $formattedMonth = $startOfMonth->translatedFormat('F Y');

        return view('daily.calendar', compact(
            'dailyStats',
            'formattedMonth',
            'month',
            'year',
            'previousMonth',
            'nextMonth'
        ));
    }
}
