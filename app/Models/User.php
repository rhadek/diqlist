<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'points_balance',
        'last_activity_date',
        'streak',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_date' => 'datetime',
        ];
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the categories for the user.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the daily assignments for the user.
     */
    public function dailyAssignments()
    {
        return $this->hasMany(DailyAssignment::class);
    }

    /**
     * Get the point transactions for the user.
     */
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Get today's tasks for the user.
     */
    public function todayTasks()
    {
        return $this->dailyAssignments()
            ->where('date', Carbon::today()->toDateString())
            ->with('task');
    }

    /**
     * Get today's completed tasks count.
     */
    public function getCompletedTodayCountAttribute()
    {
        return $this->dailyAssignments()
            ->where('date', Carbon::today()->toDateString())
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Get today's pending tasks count.
     */
    public function getPendingTodayCountAttribute()
    {
        return $this->dailyAssignments()
            ->where('date', Carbon::today()->toDateString())
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Get this week's completed tasks count.
     */
    public function getCompletedThisWeekCountAttribute()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return $this->dailyAssignments()
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Get today's points (positive and negative).
     */
    public function getTodayPointsAttribute()
    {
        $today = Carbon::today();

        return $this->pointTransactions()
            ->whereDate('created_at', $today)
            ->sum('points_change');
    }

    /**
     * Calculate completion percentage for today.
     */
    public function getTodayCompletionPercentageAttribute()
    {
        $total = $this->completedTodayCount + $this->pendingTodayCount;

        if ($total === 0) {
            return 0;
        }

        return round(($this->completedTodayCount / $total) * 100);
    }

    /**
     * Calculate completion percentage for the week.
     */
    public function getWeekCompletionPercentageAttribute()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $total = $this->dailyAssignments()
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->count();

        if ($total === 0) {
            return 0;
        }

        $completed = $this->completedThisWeekCount;

        return round(($completed / $total) * 100);
    }

    /**
     * Update the user's streak based on activity.
     */
    public function updateStreak()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Pokud uživatel byl aktivní včera a ještě nebyl aktivní dnes
        if ($this->last_activity_date && $this->last_activity_date->toDateString() === $yesterday->toDateString()) {
            // Aktualizujeme datum poslední aktivity
            $this->last_activity_date = $today;
            // Zvýšíme streak o 1
            $this->streak += 1;
            $this->save();
        }
        // Pokud uživatel je aktivní dnes a poslední aktivita nebyla včera, resetujeme streak
        elseif (!$this->last_activity_date || $this->last_activity_date->toDateString() !== $yesterday->toDateString() && $this->last_activity_date->toDateString() !== $today->toDateString()) {
            $this->last_activity_date = $today;
            $this->streak = 1; // Začínáme nový streak
            $this->save();
        }
        // Pokud uživatel je již aktivní dnes, nic neměníme
    }

    /**
     * Add points to the user's balance and record the transaction.
     */
    public function addPoints($amount, $taskId = null, $type = 'reward', $description = null)
    {
        // Aktualizace bodového zůstatku
        $this->points_balance += $amount;
        $this->save();

        // Záznam transakce
        $this->pointTransactions()->create([
            'task_id' => $taskId,
            'points_change' => $amount,
            'type' => $type,
            'description' => $description ?: "Přidáno $amount bodů",
        ]);

        // Aktualizace streaku, pokud se jedná o první aktivitu dne
        $this->updateStreak();

        return $this;
    }

    /**
     * Subtract points from the user's balance and record the transaction.
     */
    public function subtractPoints($amount, $taskId = null, $type = 'penalty', $description = null)
    {
        // Záporná hodnota pro odečtení
        $negativeAmount = -1 * abs($amount);

        // Aktualizace bodového zůstatku
        $this->points_balance += $negativeAmount;
        $this->save();

        // Záznam transakce
        $this->pointTransactions()->create([
            'task_id' => $taskId,
            'points_change' => $negativeAmount,
            'type' => $type,
            'description' => $description ?: "Odečteno " . abs($negativeAmount) . " bodů",
        ]);

        // Aktualizace streaku (i penalizace je forma aktivity)
        $this->updateStreak();

        return $this;
    }
}
