<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyAssignment extends Model
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
        'date',
        'status',
        'completed_at',
        'is_bonus',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'completed_at' => 'datetime',
        'is_bonus' => 'boolean',
    ];

    /**
     * Get the user that owns the daily assignment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task that is assigned.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Scope a query to only include today's assignments.
     */
    public function scopeToday($query)
    {
        return $query->where('date', Carbon::today()->toDateString());
    }

    /**
     * Scope a query to only include this week's assignments.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [
            Carbon::now()->startOfWeek()->toDateString(),
            Carbon::now()->endOfWeek()->toDateString(),
        ]);
    }

    /**
     * Scope a query to only include pending assignments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed assignments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include failed assignments.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Mark the assignment as completed.
     */
    public function complete()
    {
        $this->status = 'completed';
        $this->completed_at = Carbon::now();
        $this->save();

        // Pokud úkol ještě není označen jako dokončený, dokončíme ho
        if ($this->task->status !== 'completed') {
            $this->task->complete();
        }

        return $this;
    }

    /**
     * Mark the assignment as failed.
     */
    public function fail()
    {
        $this->status = 'failed';
        $this->save();

        // Pokud úkol ještě není označen jako selhaný, selžeme ho
        if ($this->task->status !== 'failed') {
            $this->task->fail();
        }

        return $this;
    }

    /**
     * Check if the assignment is for today.
     */
    public function isToday()
    {
        return $this->date->isToday();
    }

    /**
     * Check if the assignment is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->date->isPast();
    }

    /**
     * Check if assignment is past its due time today.
     */
    public function isPastDueTime()
    {
        if (!$this->task->due_date) {
            return false;
        }

        $now = Carbon::now();
        $dueTime = $this->task->due_date->format('H:i:s');
        $today = Carbon::today()->format('Y-m-d') . ' ' . $dueTime;

        return $this->isToday() && $now->gt($today);
    }

    /**
     * Handle automatic failure for overdue tasks.
     */
    public static function handleOverdueTasks()
    {
        // Najdeme všechny včerejší (a starší) nedokončené úkoly
        $overdueAssignments = self::where('status', 'pending')
            ->where('date', '<', Carbon::today()->toDateString())
            ->with('task')
            ->get();

        foreach ($overdueAssignments as $assignment) {
            $assignment->fail();
        }
    }
}
