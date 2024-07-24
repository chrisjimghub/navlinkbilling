<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Schema;

trait SchemaTableColumn
{
    public function getColumns($table, $excludeColumns = ['id', 'created_at', 'updated_at', 'deleted_at'])
    {
        // Get all columns from the 'accounts' table
        $allColumns = Schema::getColumnListing($table);

        // Filter out the excluded columns
        return array_diff($allColumns, $excludeColumns);
    }
}
