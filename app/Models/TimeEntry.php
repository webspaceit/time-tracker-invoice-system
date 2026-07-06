<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    protected $fillable = [
        'user_id', 'customer_id', 'project_id', 'description', 'start_time',
        'end_time', 'total_seconds', 'billable', 'hourly_rate'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'billable' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeRunning($query)
    {
        return $query->whereNull('end_time');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today());
    }

    public function getDurationAttribute(): int
    {
        if (!$this->end_time) {
            return now()->diffInSeconds($this->start_time);
        }
        return abs($this->total_seconds);
    }

    public function getFormattedDurationAttribute(): string
    {
        $seconds = $this->duration;
        return sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    }
}
