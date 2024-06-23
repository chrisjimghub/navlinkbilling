<?php

namespace App\Models;

use App\Models\Traits\LogsActivity;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends BaseModel
{
    use CrudTrait;
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;
    use RevisionableTrait;


    // protected $revisionFormattedFields = [
    //     // 'title'      => 'string:<strong>%s</strong>',
    //     // 'public'     => 'boolean:No|Yes',
    //     // 'modified'   => 'datetime:m/d/Y g:i A',
    //     'deleted_at' => 'isEmpty:Active|Deleted'
    // ];

    // protected $revisionFormattedFieldNames = [
    //     // 'title'      => 'Title',
    //     // 'small_name' => 'Nickname',
    //     // 'deleted_at' => 'Deleted At'
    // ];

    // // NOTE:: package error: if $revisionCreationsEnabled is enabled, factory wont worked, set to False and run Factory

    // protected $revisionCreationsEnabled = true;
    // protected $revisionEnabled = true;
    // protected $historyLimit = 500; //Maintain a maximum of 500 changes at any point of time, while cleaning up old revisions.
    // protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)

    // // NOTE:: if you want to use boot method in your child's class
    // // If you are using another bootable trait
    // // be sure to override the boot method in your model
    // // public static function boot()
    // // {
    // //     parent::boot();
    // // }

    // revision
    public function identifiableName()
    {
        return $this->name;
    }
    
}
