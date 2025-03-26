<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\DailyAssignment;
use App\Models\Category;
use App\Models\PointTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Zobrazí hlavní dashboard.
     */ 
    public function index()
    {
        $user = Auth::user();

        // Základní údaje
        $streak = $user->streak;

        // Získání úkolů na dnes
        $todayTasks = DailyAssignment::where('user_id', $user->id)
            ->where('date', Carbon::today()->toDateString())
            ->with('task')
            ->get();

        // Počet splněných úkolů dnes
        $completedToday = $user->completedTodayCount;

        // Počet zbývajících úkolů dnes
        $pendingToday = $user->pendingTodayCount;

        // Počet splněných úkolů tento týden
        $completedThisWeek = $user->completedThisWeekCount;

        // Počet všech úkolů tento týden
        $totalTasksThisWeek = DailyAssignment::where('user_id', $user->id)
            ->whereBetween('date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])
            ->count();

        // Získání bodů získaných/ztracených dnes
        $todayPoints = $user->todayPoints;

        // Získání bodů za týden
        $weekPoints = PointTransaction::where('user_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('points_change');

        // Výpočet procent dokončení pro dnešek
        $todayCompletionPercentage = $user->todayCompletionPercentage;

        // Výpočet procent dokončení pro tento týden
        $weekCompletionPercentage = $user->weekCompletionPercentage;

        // Nadcházející úkoly (zítřek a dále)
        $upcomingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where(function($query) {
                $query->whereDate('due_date', '>', Carbon::today())
                    ->orWhereNull('due_date');
            })
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Získání kategorií
        $categories = Category::where('user_id', $user->id)
            ->withCount('tasks')
            ->orderBy('name')
            ->take(5)
            ->get();

        // Týdenní přehled - data pro každý den v týdnu
        $weeklyData = [];
        $startOfWeek = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);

            $dayAssignments = DailyAssignment::where('user_id', $user->id)
                ->where('date', $currentDay->toDateString())
                ->get();

            $total = $dayAssignments->count();
            $completed = $dayAssignments->where('status', 'completed')->count();
            $completion = $total > 0 ? round(($completed / $total) * 100) : 0;

            $weeklyData[$i] = [
                'total' => $total,
                'completed' => $completed,
                'completion' => $completion,
                'date' => $currentDay->toDateString()
            ];
        }

        // Při prvním načtení dashboardu zkontrolujeme a zpracujeme prošlé úkoly
        DailyAssignment::handleOverdueTasks();

        return view('dashboard', compact(
            'user',
            'streak',
            'todayTasks',
            'completedToday',
            'pendingToday',
            'completedThisWeek',
            'totalTasksThisWeek',
            'todayPoints',
            'weekPoints',
            'todayCompletionPercentage',
            'weekCompletionPercentage',
            'upcomingTasks',
            'categories',
            'weeklyData'
        ));
    }
}
