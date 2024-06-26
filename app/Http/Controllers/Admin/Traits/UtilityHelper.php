<?php   

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Support\Str;

trait UtilityHelper
{
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
