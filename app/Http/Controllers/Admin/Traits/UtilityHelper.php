<?php   

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Support\Str;

trait UtilityHelper
{
    public function relationshipColumn($column, $label = null, $relationshipColumn = 'name')
    {
        $col = str_replace('_id', '', $column);
        $method = $this->relationshipMethodName($col);
        $currentTable = $this->crud->model->getTable();

        if (!$this->listColumnExist($column)) {
            $this->crud->column($column);
        }

        $this->crud->modifyColumn($column, [
            'label' => $label ?? $this->convertColumnToHumanReadable($col),
            'type'  => 'closure',
            'function' => function($entry) use ($method, $relationshipColumn) {
                if ($entry->{$method} == null) {
                    return;
                }
                return $entry->{$method}->{$relationshipColumn};
            },
            'searchLogic' => function ($query, $column, $searchTerm) use ($method, $relationshipColumn) {
                $query->orWhereHas($method, function ($q) use ($column, $searchTerm, $relationshipColumn) {
                    $q->where($relationshipColumn, 'like', '%'.$searchTerm.'%');
                });
            },
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable, $col, $relationshipColumn) {
                $table = $this->classInstance($this->convertToClassName($col))->getTable();
                return $query->leftJoin($table, $table.'.id', '=', $currentTable.'.'.$col.'_id')
                        ->orderBy($table.'.'.$relationshipColumn, $columnDirection)
                        ->select($currentTable.'.*');
            },
            'orderable' => true,
        ]);
    }

    public function currencyColumn($column, $label = null)
    {
        if (!$this->listColumnExist($column)) {
            $this->crud->column($column);
        }
        
        $this->currencyFormatColumn($column);
    }

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

    public function classInstance($class, $useFullPath = false) 
    {
		if ($useFullPath) {
			return new $class;
		}

		// remove App\Models\ so i could have choice
		// to provide it in parameter
		$class = str_replace('App\\Models\\','', $class);

		$class = str_replace('_id','', $class);
        $class = ucfirst(Str::camel($class));
        $class = "\\App\\Models\\".$class;
        
        return new $class;
	}



    public function relationshipMethodName($col) 
    {
        $method = str_replace('_id', '', $col);
        $method = Str::camel($method);
        
        return $method;
    }
    
    public function convertToClassName($str) 
    {
        $str = $this->relationshipMethodName($str); 
        return ucfirst($str);
    }

    public function convertColumnToHumanReadable($col) 
    {
		$col = Str::snake($col);
		
		$col = Str::endsWith($col, '_id') ? str_replace('_id', '', $col) : $col;

        $col = str_replace('_', ' ', $col);
        $col = ucwords($col);

        return $col;
	}
    
}
