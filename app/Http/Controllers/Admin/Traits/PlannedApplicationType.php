<?php 

namespace App\Http\Controllers\Admin\Traits;

trait PlannedApplicationType {

    public function plannedApplicationTypeColumn()
    {
        $this->crud->modifyColumn('planned_application_type_id', [
            // 1-n relationship
            'type'      => 'select',
            'entity'    => 'plannedApplicationType', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => "App\Models\PlannedApplicationType", // foreign key model
            // OPTIONAL
            'limit' => 50, // Limit the number of characters shown
        ]);
    }

    public function plannedApplicationTypeField()
    {
        $this->crud->modifyField('planned_application_type_id', [
            'type' => 'select',

            // optional
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            'entity'    => 'plannedApplicationType',

            // optional - manually specify the related model and attribute
            'model'     => "App\Models\PlannedApplicationType", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user

            // optional - force the related options to be a custom query, instead of all();
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }), //  you can use this to filter the results show in the select

            // since the model name or table is 3 words name (planned_application_type), we need to define relationship type
            'relation_type' => 'BelongsTo'
        ]);
    }
}