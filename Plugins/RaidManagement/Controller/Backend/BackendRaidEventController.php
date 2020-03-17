<?php

namespace RaidManagement\Controller\Backend;

use Exception;
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
        'date' => CrudGroupByOrder::DESC,
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
            ],
        ],
        [
            'name'  => 'title',
            'type'  => CrudDataTypes::STRING,
            'label' => ['key' => 'raid_event_title', 'default' => 'Title'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'editable',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'description',
            'type'  => CrudDataTypes::STRING,
            'label' => ['key' => 'raid_event_description', 'default' => 'Description'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'editable',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'date',
            'type'  => CrudDataTypes::DATETIME,
            'label' => ['key' => 'raid_event_date', 'default' => 'Date'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'deadline',
            'type'  => CrudDataTypes::DATETIME,
            'label' => ['key' => 'raid_event_deadline', 'default' => 'Deadline'],
            'crud'  => [
                'index'  => 'off',
                'view'   => 'off',
                'create' => 'off',
                'update' => 'off',
                'delete' => 'readonly',
            ],
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
            ],
        ],
        [
            'name'  => 'icon',
            'type'  => CrudDataTypes::IMAGE,
            'label' => ['key' => 'icon', 'default' => 'Icon'],
            'crud'  => [
                'index'  => 'off',
                'view'   => 'editable',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ],
        ],
    ];

    /**
     * @param array $data
     * @param string $crudAction
     *
     * @return array
     * @throws Exception
     */
    protected function convertData(array $data, string $crudAction) : array {
        if (isset($data['date'])) {
            $data['date'] = new \DateTime($data['date']);
        }
        return parent::convertData($data, $crudAction); // TODO: Change the autogenerated stub
    }

}
