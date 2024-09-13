<?php

namespace App\Http\Controllers\Admin\Traits;

trait LastEditedBy
{
    public function lastEditedBy($model)
    {
        $model->last_edited_by = auth()->check() ? auth()->user()->id : null;
        $model->saveQuietly();
    }
}
