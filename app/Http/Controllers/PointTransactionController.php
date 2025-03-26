<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PointTransactionController extends Controller
{
    /**
     * Zobrazí seznam bodových transakcí.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filtrování podle typu transakce
        $type = $request->query('type');

        // Filtrování podle období
        $period = $request->query('period', 'all');

        // Základní query
        $query = PointTransaction::where('user_id', $user->id);

        // Aplikujeme filtry, pokud jsou zadány
        if ($type) {
            $query->where('type', $type);
        }

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                break;
            // 'all' - bez filtru
        }

        // Řazení od nejnovějších
        $query->orderBy('created_at', 'desc');

        // Stránkování
        $transactions = $query->paginate(15)->withQueryString();

        // Statistiky pro aktuální zobrazení
        $totalPoints = $query->sum('points_change');
        $positivePoints = $query->clone()->where('points_change', '>', 0)->sum('points_change');
        $negativePoints = $query->clone()->where('points_change', '<', 0)->sum('points_change');

        return view('points.index', compact(
            'transactions',
            'type',
            'period',
            'totalPoints',
            'positivePoints',
            'negativePoints'
        ));
    }

    /**
     * Zobrazí formulář pro ruční úpravu bodů.
     */
    public function create()
    {
        return view('points.create');
    }

    /**
     * Uloží novou ruční úpravu bodů.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'points_change' => 'required|integer|not_in:0',
            'description' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Vytvoření transakce
        $transaction = new PointTransaction();
        $transaction->user_id = $user->id;
        $transaction->points_change = $validated['points_change'];
        $transaction->type = 'adjustment';
        $transaction->description = $validated['description'];
        $transaction->save();

        // Aktualizace bodového zůstatku uživatele
        $user->points_balance += $validated['points_change'];
        $user->save();

        // Aktualizace streaku, pokud se jedná o první aktivitu dne
        $user->updateStreak();

        $message = $validated['points_change'] > 0
            ? 'Body byly úspěšně přidány!'
            : 'Body byly úspěšně odečteny!';

        return redirect()->route('points.index')
            ->with('success', $message);
    }

    /**
     * Zobrazí statistiky bodů v čase.
     */
    public function stats(Request $request)
    {
        $user = Auth::user();

        // Výběr období
        $period = $request->query('period', 'week');

        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->subWeek();
                $endDate = Carbon::now();
                $groupBy = 'day';
                break;
            case 'month':
                $startDate = Carbon::now()->subMonth();
                $endDate = Carbon::now();
                $groupBy = 'day';
                break;
            case 'year':
                $startDate = Carbon::now()->subYear();
                $endDate = Carbon::now();
                $groupBy = 'month';
                break;
            default:
                $startDate = Carbon::now()->subWeek();
                $endDate = Carbon::now();
                $groupBy = 'day';
        }

        // Získání dat pro graf
        $transactions = PointTransaction::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Příprava dat pro graf podle zvoleného seskupení
        $chartData = [];

        if ($groupBy === 'day') {
            // Seskupení podle dnů
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dayTransactions = $transactions->filter(function($transaction) use ($date) {
                    return Carbon::parse($transaction->created_at)->isSameDay($date);
                });

                $chartData[] = [
                    'date' => $date->format('Y-m-d'),
                    'positive' => $dayTransactions->where('points_change', '>', 0)->sum('points_change'),
                    'negative' => abs($dayTransactions->where('points_change', '<', 0)->sum('points_change')),
                    'net' => $dayTransactions->sum('points_change')
                ];
            }
        } else {
            // Seskupení podle měsíců
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
                $monthTransactions = $transactions->filter(function($transaction) use ($date) {
                    $txDate = Carbon::parse($transaction->created_at);
                    return $txDate->month === $date->month && $txDate->year === $date->year;
                });

                $chartData[] = [
                    'date' => $date->format('Y-m'),
                    'positive' => $monthTransactions->where('points_change', '>', 0)->sum('points_change'),
                    'negative' => abs($monthTransactions->where('points_change', '<', 0)->sum('points_change')),
                    'net' => $monthTransactions->sum('points_change')
                ];
            }
        }

        // Celkové statistiky pro zobrazené období
        $totalPoints = $transactions->sum('points_change');
        $positivePoints = $transactions->where('points_change', '>', 0)->sum('points_change');
        $negativePoints = abs($transactions->where('points_change', '<', 0)->sum('points_change'));

        // Zjistíme nejúspěšnější den/měsíc
        $mostSuccessful = collect($chartData)->sortByDesc('net')->first();

        // Zjistíme nejméně úspěšný den/měsíc
        $leastSuccessful = collect($chartData)->sortBy('net')->first();

        return view('points.stats', compact(
            'chartData',
            'period',
            'groupBy',
            'totalPoints',
            'positivePoints',
            'negativePoints',
            'mostSuccessful',
            'leastSuccessful'
        ));
    }
}
