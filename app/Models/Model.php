<?php

namespace App\Models;

use App\Models\Traits\LogsActivity;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends BaseModel
{
    use CrudTrait;
    use HasFactory;
    use LogsActivity;
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;


    // NOTE:: Revision
    public static function boot() 
    {
        parent::boot();
    }

    // revision
    public function identifiableName()
    {
        return $this->name;
    }
    
}
