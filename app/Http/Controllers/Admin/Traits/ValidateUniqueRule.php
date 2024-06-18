<?php 

namespace App\Http\Controllers\Admin\Traits;

trait ValidateUniqueRule {

    public function validateUniqueRule($field = 'name', $table = null)
    {
        if (!$table) {
            $table = $this->crud->model->getTable();
        }

        $id = $this->crud->getCurrentEntryId();

        // edit/update
        if ($id) {
            return 'required|string|min:2|unique:'.$table.','.$field.','.$id;
        }

        return 'required|string|min:2|unique:'.$table.','.$field.'';
    }

}