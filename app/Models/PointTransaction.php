<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PointTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'points_change',
        'type',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'points_change' => 'integer',
    ];

    /**
     * Get the user that owns the point transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task associated with the point transaction.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Scope a query to only include reward transactions.
     */
    public function scopeRewards($query)
    {
        return $query->where('type', 'reward');
    }

    /**
     * Scope a query to only include penalty transactions.
     */
    public function scopePenalties($query)
    {
        return $query->where('type', 'penalty');
    }

    /**
     * Scope a query to only include bonus transactions.
     */
    public function scopeBonuses($query)
    {
        return $query->where('type', 'bonus');
    }

    /**
     * Scope a query to only include adjustment transactions.
     */
    public function scopeAdjustments($query)
    {
        return $query->where('type', 'adjustment');
    }

    /**
     * Scope a query to only include positive transactions.
     */
    public function scopePositive($query)
    {
        return $query->where('points_change', '>', 0);
    }

    /**
     * Scope a query to only include negative transactions.
     */
    public function scopeNegative($query)
    {
        return $query->where('points_change', '<', 0);
    }

    /**
     * Scope a query to only include today's transactions.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Scope a query to only include this week's transactions.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);
    }

    /**
     * Scope a query to only include this month's transactions.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ]);
    }

    /**
     * Get formatted points change with sign.
     */
    public function getFormattedPointsChangeAttribute()
    {
        if ($this->points_change > 0) {
            return '+' . $this->points_change;
        }

        return (string) $this->points_change;
    }

    /**
     * Get color based on transaction type.
     */
    public function getColorAttribute()
    {
        if ($this->points_change > 0) {
            return 'text-green-600 dark:text-green-400';
        } else {
            return 'text-red-600 dark:text-red-400';
        }
    }

    /**
     * Create a reward transaction.
     */
    public static function createReward($userId, $points, $taskId = null, $description = null)
    {
        return self::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'points_change' => abs($points),
            'type' => 'reward',
            'description' => $description,
        ]);
    }

    /**
     * Create a penalty transaction.
     */
    public static function createPenalty($userId, $points, $taskId = null, $description = null)
    {
        return self::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'points_change' => -abs($points),
            'type' => 'penalty',
            'description' => $description,
        ]);
    }

    /**
     * Create a bonus transaction.
     */
    public static function createBonus($userId, $points, $description = null)
    {
        return self::create([
            'user_id' => $userId,
            'points_change' => abs($points),
            'type' => 'bonus',
            'description' => $description ?? 'Bonusové body',
        ]);
    }

    /**
     * Create an adjustment transaction.
     */
    public static function createAdjustment($userId, $points, $description = null)
    {
        return self::create([
            'user_id' => $userId,
            'points_change' => $points, // Může být kladné i záporné
            'type' => 'adjustment',
            'description' => $description ?? 'Ruční úprava bodů',
        ]);
    }
}
