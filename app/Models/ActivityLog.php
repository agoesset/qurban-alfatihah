<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'event',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the subject (the model that was acted upon)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the causer (the user who performed the action)
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for filtering by log name
     */
    public function scopeInLog($query, string $logName)
    {
        return $query->where('log_name', $logName);
    }

    /**
     * Scope for filtering by causer
     */
    public function scopeCausedBy($query, Model $causer)
    {
        return $query->where('causer_type', get_class($causer))
            ->where('causer_id', $causer->getKey());
    }

    /**
     * Scope for filtering by subject
     */
    public function scopeForSubject($query, Model $subject)
    {
        return $query->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope for filtering by event
     */
    public function scopeForEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Get the user who performed the action (alias for causer)
     */
    public function getPerformedByAttribute(): ?Model
    {
        return $this->causer;
    }

    /**
     * Get formatted description with replaced placeholders
     */
    public function getFormattedDescriptionAttribute(): string
    {
        $description = $this->description;

        // Replace {causer} placeholder
        if ($this->causer) {
            $causerName = $this->causer->name ?? $this->causer->email ?? 'System';
            $description = str_replace('{causer}', $causerName, $description);
        }

        // Replace {subject} placeholder
        if ($this->subject) {
            $subjectIdentifier = $this->subject->kode_hewan ?? $this->subject->nama_kategori ?? $this->subject->nama ?? $this->subject->id;
            $description = str_replace('{subject}', $subjectIdentifier, $description);
        }

        return $description;
    }
}
