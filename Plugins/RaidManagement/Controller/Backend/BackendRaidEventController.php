<?php

namespace RaidManagement\Controller\Backend;

use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Oforge\Engine\Modules\CRUD\Controller\Backend\BaseCrudController;
use Oforge\Engine\Modules\CRUD\Enum\CrudDataTypes;
use Oforge\Engine\Modules\CRUD\Enum\CrudGroupByOrder;
use RaidManagement\Models\RaidEvent;

/**
 * Class BackendRaidEventController
 * @EndpointClass(path="/backend/raidevent", name="backend_raidevent_controller", assetScope="Backend")
 */
class BackendRaidEventController extends BaseCrudController {

    protected $model = RaidEvent::class;

    protected $indexOrderBy = [
<<<<<<< HEAD
      'orderBy' => 'date',
      'order'   => 'DESC',
=======
        'date' => CrudGroupByOrder::DESC,
>>>>>>> bb0721f8f17227fbe0a9f6e228bf7db74555e50e
    ];

    protected $modelProperties = [
        [
            'name'  => 'id',
            'type'  => CrudDataTypes::INT,
            'label' => ['key' => 'id', 'default' => 'Id'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'readonly',
                'update' => 'readonly',
                'delete' => 'readonly',
            ]
        ],
        [
            'name'  => 'title',
            'type'  => CrudDataTypes::STRING,
            'label' => ['key' => 'raid_event_title', 'default' => 'Title'],
            'crud'  => [
                'index'  => 'editable',
                'view'   => 'editable',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ]
        ],
        [
            'name'  => 'description',
            'type'  => CrudDataTypes::STRING,
            'label' => ['key' => 'raid_event_description', 'default' => 'Description'],
            'crud'  => [
                'index'  => 'editable',
                'view'   => 'editable',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ]
        ],
        [
            'name'  => 'date',
            'type'  => CrudDataTypes::DATETIME,
            'label' => ['key' => 'raid_event_date', 'default' => 'Date'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'off',
                'update' => 'editable',
                'delete' => 'readonly',
            ]
        ],
        [
            'name'  => 'deadline',
            'type'  => CrudDataTypes::DATETIME,
            'label' => ['key' => 'raid_event_deadline', 'default' => 'Deadline'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'off',
                'update' => 'editable',
                'delete' => 'readonly',
            ]
        ],
        [
            'name'  => 'active',
            'type'  => CrudDataTypes::BOOL,
            'label' => ['key' => 'raid_event_active', 'default' => 'Active'],
            'crud'  => [
                'index'  => 'editable',
                'view'   => 'editable',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ]
        ],
    ];

}
