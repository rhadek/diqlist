<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\DailyAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Zobrazí hlavní dashboard.
     */
    public function index()
    {
        $user = Auth::user();

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

        // Získání bodů získaných/ztracených dnes
        $todayPoints = $user->todayPoints;

        // Výpočet procent dokončení pro dnešek
        $todayCompletionPercentage = $user->todayCompletionPercentage;

        // Výpočet procent dokončení pro tento týden
        $weekCompletionPercentage = $user->weekCompletionPercentage;

        // Počet dnů v řadě s aktivitou
        $streak = $user->streak;

        // Nadcházející úkoly (zítřek a dále)
        $upcomingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('due_date', '>', Carbon::today())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Při prvním načtení dashboardu zkontrolujeme a zpracujeme prošlé úkoly
        DailyAssignment::handleOverdueTasks();

        return view('dashboard', compact(
            'user',
            'todayTasks',
            'completedToday',
            'pendingToday',
            'completedThisWeek',
            'todayPoints',
            'todayCompletionPercentage',
            'weekCompletionPercentage',
            'streak',
            'upcomingTasks'
        ));
    }
}
