<?php

namespace App\Http\Controllers\Admin\Traits;

trait CrudColumn
{
    public function listColumnExist($columnName)
    {
        // check if column exist in list operation
        if (
            !array_key_exists('list.columns', $this->crud->settings()) || // no columns yet
            !array_key_exists($columnName, $this->crud->settings()['list.columns']) // column not yet created
        ) {
            return false;
        }

        return true;
    }
}
