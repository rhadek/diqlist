<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'points_value',
        'recurring',
        'recurring_type',
        'recurring_interval',
        'is_recurring_parent',
        'parent_task_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'recurring' => 'boolean',
        'is_recurring_parent' => 'boolean',
        'priority' => 'integer',
        'points_value' => 'integer',
    ];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the task belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the daily assignments for the task.
     */
    public function dailyAssignments()
    {
        return $this->hasMany(DailyAssignment::class);
    }

    /**
     * Get the point transactions associated with the task.
     */
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Get the parent task if this is a child of a recurring task.
     */
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    /**
     * Get the recurring child tasks if this is a recurring parent task.
     */
    public function childTasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    /**
     * Scope a query to only include pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed tasks.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include failed tasks.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include tasks with high priority.
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 3);
    }

    /**
     * Scope a query to only include tasks with medium priority.
     */
    public function scopeMediumPriority($query)
    {
        return $query->where('priority', 2);
    }

    /**
     * Scope a query to only include tasks with low priority.
     */
    public function scopeLowPriority($query)
    {
        return $query->where('priority', 1);
    }

    /**
     * Scope a query to only include tasks due today.
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', Carbon::today());
    }

    /**
     * Scope a query to only include tasks due this week.
     */
    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ]);
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', Carbon::now());
    }

    /**
     * Scope a query to only include tasks that have a daily assignment for today.
     */
    public function scopeAssignedToday($query)
    {
        return $query->whereHas('dailyAssignments', function ($query) {
            $query->where('date', Carbon::today()->toDateString());
        });
    }

    /**
     * Mark the task as completed and handle associated actions.
     */
    public function complete()
    {
        $this->status = 'completed';
        $this->save();

        // Najdeme dnešní přiřazení tohoto úkolu, pokud existuje
        $assignment = $this->dailyAssignments()
            ->where('date', Carbon::today()->toDateString())
            ->first();

        if ($assignment) {
            $assignment->status = 'completed';
            $assignment->completed_at = Carbon::now();
            $assignment->save();
        }

        // Přidáme body uživateli
        $this->user->addPoints(
            $this->points_value,
            $this->id,
            'reward',
            "Splnění úkolu: {$this->title}"
        );

        // Pokud jde o opakující se úkol, vygenerujeme další instanci
        if ($this->recurring && !$this->is_recurring_parent) {
            $this->generateNextRecurringTask();
        }

        return $this;
    }

    /**
     * Mark the task as failed and handle associated actions.
     */
    public function fail()
    {
        $this->status = 'failed';
        $this->save();

        // Najdeme dnešní přiřazení tohoto úkolu, pokud existuje
        $assignment = $this->dailyAssignments()
            ->where('date', Carbon::today()->toDateString())
            ->first();

        if ($assignment) {
            $assignment->status = 'failed';
            $assignment->save();
        }

        // Penalizace pro uživatele (dvojnásobek bodové hodnoty)
        $penaltyPoints = $this->points_value * 2;
        $this->user->subtractPoints(
            $penaltyPoints,
            $this->id,
            'penalty',
            "Nesplnění úkolu: {$this->title}"
        );

        // Pokud jde o opakující se úkol, vygenerujeme další instanci
        if ($this->recurring && !$this->is_recurring_parent) {
            $this->generateNextRecurringTask();
        }

        return $this;
    }

    /**
     * Generate the next instance of a recurring task.
     */
    protected function generateNextRecurringTask()
    {
        if (!$this->recurring || !$this->due_date) {
            return;
        }

        $nextDueDate = null;

        // Výpočet data pro příští úkol podle typu opakování
        switch ($this->recurring_type) {
            case 'daily':
                $nextDueDate = $this->due_date->copy()->addDays($this->recurring_interval ?? 1);
                break;
            case 'weekly':
                $nextDueDate = $this->due_date->copy()->addWeeks($this->recurring_interval ?? 1);
                break;
            case 'monthly':
                $nextDueDate = $this->due_date->copy()->addMonths($this->recurring_interval ?? 1);
                break;
            default:
                return;
        }

        // Vytvoření nového úkolu
        $newTask = $this->replicate(['status']);
        $newTask->status = 'pending';
        $newTask->due_date = $nextDueDate;
        $newTask->parent_task_id = $this->parent_task_id ?? $this->id;
        $newTask->save();

        return $newTask;
    }

    /**
     * Assign a task to today.
     */
    public function assignToToday()
    {
        // Pokud už je úkol přiřazen na dnešek, nic neděláme
        $existingAssignment = $this->dailyAssignments()
            ->where('date', Carbon::today()->toDateString())
            ->first();

        if ($existingAssignment) {
            return $existingAssignment;
        }

        // Vytvoření nového přiřazení
        return $this->dailyAssignments()->create([
            'user_id' => $this->user_id,
            'date' => Carbon::today()->toDateString(),
            'status' => 'pending',
        ]);
    }

    /**
     * Get the priority as a text.
     */
    public function getPriorityTextAttribute()
    {
        switch ($this->priority) {
            case 1:
                return 'Nízká';
            case 2:
                return 'Střední';
            case 3:
                return 'Vysoká';
            default:
                return 'Neznámá';
        }
    }

    /**
     * Calculate points based on priority.
     */
    public function calculatePointsValue()
    {
        // Pokud už máme nastavenou hodnotu, vrátíme ji
        if ($this->points_value > 0) {
            return $this->points_value;
        }

        // Výchozí body podle priority
        switch ($this->priority) {
            case 1: // Nízká
                return rand(1, 3);
            case 2: // Střední
                return rand(3, 5);
            case 3: // Vysoká
                return rand(5, 10);
            default:
                return 5;
        }
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Check if the task is due today.
     */
    public function isDueToday()
    {
        return $this->due_date && $this->due_date->isToday();
    }

    /**
     * Check if the task is assigned for today.
     */
    public function isAssignedToday()
    {
        return $this->dailyAssignments()
            ->where('date', Carbon::today()->toDateString())
            ->exists();
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Automaticky nastavíme bodovou hodnotu při vytvoření
        static::creating(function ($task) {
            if (!$task->points_value || $task->points_value <= 0) {
                $task->points_value = $task->calculatePointsValue();
            }
        });

        // Pokud má úkol termín dnes a není přiřazen na dnešek, automaticky ho přiřadíme
        static::created(function ($task) {
            if ($task->isDueToday() && !$task->isAssignedToday()) {
                $task->assignToToday();
            }
        });
    }
}
