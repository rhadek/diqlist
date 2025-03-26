<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'color_code',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Get the user that owns the category.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tasks for the category.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the count of active tasks in this category.
     */
    public function getPendingTasksCountAttribute()
    {
        return $this->tasks()->where('status', 'pending')->count();
    }

    /**
     * Get the count of completed tasks in this category.
     */
    public function getCompletedTasksCountAttribute()
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    /**
     * Get the count of failed tasks in this category.
     */
    public function getFailedTasksCountAttribute()
    {
        return $this->tasks()->where('status', 'failed')->count();
    }

    /**
     * Get the completion percentage for this category.
     */
    public function getCompletionPercentageAttribute()
    {
        $total = $this->pendingTasksCount + $this->completedTasksCount + $this->failedTasksCount;

        if ($total === 0) {
            return 0;
        }

        return round(($this->completedTasksCount / $total) * 100);
    }

    /**
     * Set default color code if not provided.
     */
    protected static function booted()
    {
        static::creating(function ($category) {
            if (!$category->color_code) {
                // Základní sada barev pro automatický výběr
                $colors = [
                    '#3B82F6', // Modrá
                    '#10B981', // Zelená
                    '#F59E0B', // Oranžová
                    '#8B5CF6', // Fialová
                    '#EC4899', // Růžová
                    '#EF4444', // Červená
                    '#6366F1', // Indigo
                    '#14B8A6', // Tyrkysová
                ];

                // Náhodný výběr barvy
                $category->color_code = $colors[array_rand($colors)];
            }
        });
    }
}
