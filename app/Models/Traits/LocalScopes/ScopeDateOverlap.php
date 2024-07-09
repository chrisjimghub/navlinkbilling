<?php

namespace App\Models\Traits\LocalScopes;

trait ScopeDateOverlap
{
    public function scopeDateOverlap($query, $date_start, $date_end, $date_start_column = 'date_start', $date_end_column = 'date_end')
    {
        return $query->where(function ($query) use ($date_start, $date_end, $date_start_column, $date_end_column) {
            $query->whereBetween($date_start_column, [$date_start, $date_end])
                  ->orWhereBetween($date_end_column, [$date_start, $date_end])
                  ->orWhereRaw('? BETWEEN '.$date_start_column.' and '.$date_end_column, [$date_start])
                  ->orWhereRaw('? BETWEEN '.$date_start_column.' and '.$date_end_column, [$date_end]);
        });

        // return $query->whereBetween('date_start', [$date_start, $date_end]) 
        //     ->orWhereBetween('date_end', [$date_start, $date_end]) 
        //     ->orWhereRaw('? BETWEEN date_start and date_end', [$date_start]) 
        //     ->orWhereRaw('? BETWEEN date_start and date_end', [$date_end]);
    }
}
