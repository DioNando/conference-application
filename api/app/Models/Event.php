<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the formatted start date.
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('Y-m-d');
    }

    /**
     * Get the formatted end date.
     */
    public function getFormattedEndDateAttribute()
    {
        return $this->end_date->format('Y-m-d');
    }

    /**
     * Get the dates for this event as an array.
     */
    public function getDatesArray(): array
    {
        $dates = [];
        $current = clone $this->start_date;

        while ($current <= $this->end_date) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        return $dates;
    }

    /**
     * Scope a query to only include active events.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
