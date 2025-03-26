<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\DailyAssignment;
use App\Models\PointTransaction;
use App\Models\Category;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Zobrazí hlavní stránku statistik.
     */
    public function index()
    {
        $user = Auth::user();

        // Celkové statistiky
        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)->where('status', 'completed')->count();
        $failedTasks = Task::where('user_id', $user->id)->where('status', 'failed')->count();
        $pendingTasks = Task::where('user_id', $user->id)->where('status', 'pending')->count();

        // Výpočet úspěšnosti (pokud neexistují žádné úkoly, úspěšnost je 0)
        $totalCompleted = $completedTasks + $failedTasks;
        $successRate = $totalCompleted > 0 ? round(($completedTasks / $totalCompleted) * 100) : 0;

        // Bodová bilance
        $totalPoints = $user->points_balance;
        $positivePoints = PointTransaction::where('user_id', $user->id)->where('points_change', '>', 0)->sum('points_change');
        $negativePoints = PointTransaction::where('user_id', $user->id)->where('points_change', '<', 0)->sum('points_change');

        // Denní průměr
        $oldestTask = Task::where('user_id', $user->id)->oldest('created_at')->first();
        $daysActive = $oldestTask ? Carbon::parse($oldestTask->created_at)->diffInDays(Carbon::now()) + 1 : 1;
        $tasksPerDay = $daysActive > 0 ? round($totalTasks / $daysActive, 1) : 0;
        $completedPerDay = $daysActive > 0 ? round($completedTasks / $daysActive, 1) : 0;

        // Streak
        $currentStreak = $user->streak;

        // Nejlepší a nejhorší kategorie
        $categories = Category::where('user_id', $user->id)
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->having('tasks_count', '>', 0)
            ->get()
            ->map(function ($category) {
                $category->success_rate = $category->tasks_count > 0
                    ? round(($category->completed_tasks_count / $category->tasks_count) * 100)
                    : 0;
                return $category;
            });

        $bestCategory = $categories->sortByDesc('success_rate')->first();
        $worstCategory = $categories->sortBy('success_rate')->first();

        // Nejúspěšnější den v týdnu
        $dayStats = DailyAssignment::where('user_id', $user->id)
            ->select(
                DB::raw('DAYOFWEEK(date) as day_number'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->groupBy('day_number')
            ->get()
            ->map(function ($stat) {
                $dayNames = [
                    1 => 'Neděle',
                    2 => 'Pondělí',
                    3 => 'Úterý',
                    4 => 'Středa',
                    5 => 'Čtvrtek',
                    6 => 'Pátek',
                    7 => 'Sobota',
                ];
                $stat->day_name = $dayNames[$stat->day_number];
                $stat->success_rate = $stat->total > 0 ? round(($stat->completed / $stat->total) * 100) : 0;
                return $stat;
            });

        $bestDay = $dayStats->sortByDesc('success_rate')->first();
        $worstDay = $dayStats->sortBy('success_rate')->first();

        // Data pro týdenní graf aktivity
        $weekActivityData = $this->getWeekActivityData($user->id);

        // Data pro graf denní aktivity
        $dailyActivityData = $this->getDailyActivityData($user->id);

        return view('statistics.index', compact(
            'totalTasks',
            'completedTasks',
            'failedTasks',
            'pendingTasks',
            'successRate',
            'totalPoints',
            'positivePoints',
            'negativePoints',
            'tasksPerDay',
            'completedPerDay',
            'currentStreak',
            'bestCategory',
            'worstCategory',
            'bestDay',
            'worstDay',
            'weekActivityData',
            'dailyActivityData'
        ));
    }

    /**
     * Získá data pro graf týdenní aktivity.
     */
    private function getWeekActivityData($userId)
    {
        // Poslední 4 týdny
        $weeks = [];
        for ($i = 0; $i < 4; $i++) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();

            $weeks[] = [
                'start' => $startOfWeek,
                'end' => $endOfWeek,
                'label' => $startOfWeek->format('d.m') . ' - ' . $endOfWeek->format('d.m'),
            ];
        }

        // Obrátíme pořadí, aby nejstarší týden byl první
        $weeks = array_reverse($weeks);

        $weekData = [];

        foreach ($weeks as $week) {
            $assignments = DailyAssignment::where('user_id', $userId)
                ->whereBetween('date', [$week['start']->toDateString(), $week['end']->toDateString()])
                ->get();

            $total = $assignments->count();
            $completed = $assignments->where('status', 'completed')->count();
            $failed = $assignments->where('status', 'failed')->count();

            $weekData[] = [
                'label' => $week['label'],
                'total' => $total,
                'completed' => $completed,
                'failed' => $failed,
                'pending' => $total - $completed - $failed,
                'success_rate' => $total > 0 ? round(($completed / $total) * 100) : 0,
            ];
        }

        return $weekData;
    }

    /**
     * Získá data pro graf denní aktivity.
     */
    private function getDailyActivityData($userId)
    {
        // Posledních 7 dní
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);

            $days[] = [
                'date' => $day,
                'label' => $day->format('d.m.'),
                'day_name' => $day->translatedFormat('D'),
            ];
        }

        $dayData = [];

        foreach ($days as $day) {
            $assignments = DailyAssignment::where('user_id', $userId)
                ->where('date', $day['date']->toDateString())
                ->get();

            $total = $assignments->count();
            $completed = $assignments->where('status', 'completed')->count();

            $points = PointTransaction::where('user_id', $userId)
                ->whereDate('created_at', $day['date'])
                ->sum('points_change');

            $dayData[] = [
                'label' => $day['label'],
                'day_name' => $day['day_name'],
                'total' => $total,
                'completed' => $completed,
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100) : 0,
                'points' => $points,
            ];
        }

        return $dayData;
    }

    /**
     * Zobrazí statistiky produktivity.
     */
    public function productivity()
    {
        $user = Auth::user();

        // Celková produktivita po měsících
        $monthlyData = $this->getMonthlyProductivityData($user->id);

        // Průměrná produktivita podle dne v týdnu
        $weekdayData = $this->getWeekdayProductivityData($user->id);

        // Produktivita podle denní doby
        $timeOfDayData = $this->getTimeOfDayProductivityData($user->id);

        // Nejproduktivnější měsíc
        $mostProductiveMonth = collect($monthlyData)->sortByDesc('completion_rate')->first();

        // Nejproduktivnější den v týdnu
        $mostProductiveWeekday = collect($weekdayData)->sortByDesc('completion_rate')->first();

        // Nejproduktivnější denní doba
        $mostProductiveTimeOfDay = collect($timeOfDayData)->sortByDesc('completion_rate')->first();

        return view('statistics.productivity', compact(
            'monthlyData',
            'weekdayData',
            'timeOfDayData',
            'mostProductiveMonth',
            'mostProductiveWeekday',
            'mostProductiveTimeOfDay'
        ));
    }

    /**
     * Získá data pro měsíční produktivitu.
     */
    private function getMonthlyProductivityData($userId)
    {
        // Posledních 6 měsíců
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $months[] = [
                'start' => $startOfMonth,
                'end' => $endOfMonth,
                'label' => $month->translatedFormat('F Y'),
            ];
        }

        $monthlyData = [];

        foreach ($months as $month) {
            $assignments = DailyAssignment::where('user_id', $userId)
                ->whereBetween('date', [$month['start']->toDateString(), $month['end']->toDateString()])
                ->get();

            $total = $assignments->count();
            $completed = $assignments->where('status', 'completed')->count();

            $points = PointTransaction::where('user_id', $userId)
                ->whereBetween('created_at', [$month['start'], $month['end']])
                ->sum('points_change');

            $monthlyData[] = [
                'label' => $month['label'],
                'total' => $total,
                'completed' => $completed,
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100) : 0,
                'points' => $points,
            ];
        }

        return $monthlyData;
    }

    /**
     * Získá data pro produktivitu podle dne v týdnu.
     */
    private function getWeekdayProductivityData($userId)
    {
        $weekdays = [
            1 => 'Pondělí',
            2 => 'Úterý',
            3 => 'Středa',
            4 => 'Čtvrtek',
            5 => 'Pátek',
            6 => 'Sobota',
            7 => 'Neděle',
        ];

        $weekdayData = [];

        foreach ($weekdays as $dayNumber => $dayName) {
            $assignments = DailyAssignment::where('user_id', $userId)
                ->whereRaw("DAYOFWEEK(date) = " . ($dayNumber % 7 + 1))
                ->get();

            $total = $assignments->count();
            $completed = $assignments->where('status', 'completed')->count();

            $weekdayData[] = [
                'label' => $dayName,
                'day_number' => $dayNumber,
                'total' => $total,
                'completed' => $completed,
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100) : 0,
            ];
        }

        return $weekdayData;
    }

    /**
     * Získá data pro produktivitu podle denní doby.
     */
    private function getTimeOfDayProductivityData($userId)
    {
        $timeSlots = [
            'morning' => ['label' => 'Ráno (6-12)', 'start' => 6, 'end' => 11],
            'afternoon' => ['label' => 'Odpoledne (12-18)', 'start' => 12, 'end' => 17],
            'evening' => ['label' => 'Večer (18-24)', 'start' => 18, 'end' => 23],
            'night' => ['label' => 'Noc (0-6)', 'start' => 0, 'end' => 5],
        ];

        $timeOfDayData = [];

        foreach ($timeSlots as $slotKey => $slot) {
            $completedAssignments = DailyAssignment::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereNotNull('completed_at')
                ->whereRaw("HOUR(completed_at) BETWEEN {$slot['start']} AND {$slot['end']}")
                ->count();

            $totalAssignments = DailyAssignment::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereNotNull('completed_at')
                ->count();

            $timeOfDayData[] = [
                'label' => $slot['label'],
                'slot_key' => $slotKey,
                'completed' => $completedAssignments,
                'total' => $totalAssignments,
                'completion_rate' => $totalAssignments > 0
                    ? round(($completedAssignments / $totalAssignments) * 100)
                    : 0,
            ];
        }

        return $timeOfDayData;
    }

    /**
     * Zobrazí statistiky kategorií.
     */
    public function categories()
    {
        $user = Auth::user();

        // Získání kategorií a jejich statistik
        $categories = Category::where('user_id', $user->id)
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withCount(['tasks as failed_tasks_count' => function ($query) {
                $query->where('status', 'failed');
            }])
            ->withCount(['tasks as pending_tasks_count' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->get()
            ->map(function ($category) {
                $category->completion_rate = $category->tasks_count > 0
                    ? round(($category->completed_tasks_count / $category->tasks_count) * 100)
                    : 0;
                return $category;
            });

        // Počet nezařazených úkolů
        $uncategorizedTasks = Task::where('user_id', $user->id)
            ->whereNull('category_id')
            ->count();

        $uncategorizedCompleted = Task::where('user_id', $user->id)
            ->whereNull('category_id')
            ->where('status', 'completed')
            ->count();

        $uncategorizedCompletionRate = $uncategorizedTasks > 0
            ? round(($uncategorizedCompleted / $uncategorizedTasks) * 100)
            : 0;

        // Příprava dat pro koláčový graf
        $categoryChartData = [];

        foreach ($categories as $category) {
            if ($category->tasks_count > 0) {
                $categoryChartData[] = [
                    'label' => $category->name,
                    'value' => $category->tasks_count,
                    'color' => $category->color_code,
                ];
            }
        }

        // Přidáme nezařazené, pokud existují
        if ($uncategorizedTasks > 0) {
            $categoryChartData[] = [
                'label' => 'Nezařazené',
                'value' => $uncategorizedTasks,
                'color' => '#6B7280', // Šedá
            ];
        }

        return view('statistics.categories', compact(
            'categories',
            'uncategorizedTasks',
            'uncategorizedCompleted',
            'uncategorizedCompletionRate',
            'categoryChartData'
        ));
    }
}
