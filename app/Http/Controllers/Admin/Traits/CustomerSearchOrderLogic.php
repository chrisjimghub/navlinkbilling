<?php

namespace App\Http\Controllers\Admin\Traits;

trait CustomerSearchOrderLogic {

    private function customerSearchLogic()
    {
        return function ($query, $column, $searchTerm) {
            $query->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('last_name', 'like', '%'.$searchTerm.'%');
        };
    }

    // TODO:: 
    private function customerAsRelationshipSearchLogic()
    {
        
    }

}