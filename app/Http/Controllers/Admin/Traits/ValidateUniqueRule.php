<?php 

namespace App\Http\Controllers\Admin\Traits;

trait ValidateUniqueRule {

    public function validateUniqueRule($table, $field = 'name')
    {
        $id = $this->crud->getCurrentEntryId();
        
        // edit/update
        if ($id) {
            return 'required|string|unique:'.$table.','.$field.',' . $id;
        }

        return 'required|string|unique:'.$table.','.$field.''; 
    }

}