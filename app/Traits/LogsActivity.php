<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->logActivity('created', 'Created ' . class_basename($model) . ' {subject}');
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']); // Ignore updated_at changes

            if (!empty($changes)) {
                $model->logActivity('updated', 'Updated ' . class_basename($model) . ' {subject}', [
                    'old' => $model->getOriginal(),
                    'new' => $changes,
                ]);
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', 'Deleted ' . class_basename($model) . ' {subject}');
        });
    }

    /**
     * Log an activity
     */
    public function logActivity(string $event, string $description, array $properties = []): ActivityLog
    {
        return ActivityLog::create([
            'log_name' => $this->getActivityLogName(),
            'description' => $description,
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => array_merge($this->getActivityProperties(), $properties),
            'event' => $event,
        ]);
    }

    /**
     * Get custom activity log name for this model
     */
    protected function getActivityLogName(): string
    {
        return 'default';
    }

    /**
     * Get additional properties to be logged
     */
    protected function getActivityProperties(): array
    {
        return [];
    }

    /**
     * Get all activity logs for this model
     */
    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject')->latest();
    }

    /**
     * Get the last activity log
     */
    public function lastActivity(): ?ActivityLog
    {
        return $this->activities()->first();
    }
}
