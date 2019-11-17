<?php

namespace RaidManagement\Controller\Backend;

use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Oforge\Engine\Modules\CRUD\Controller\Backend\BaseCrudController;
use Oforge\Engine\Modules\CRUD\Enum\CrudDataTypes;
use Oforge\Engine\Modules\CRUD\Enum\CrudFilterType;
use Oforge\Engine\Modules\CRUD\Enum\CrudGroupByOrder;
use RaidManagement\Models\RaidEvent;
use RaidManagement\Models\RaidMember;

/**
 * Class BackendRaidMemberController
 * @package RaidManagement\Controller\Backend
 * @EndpointClass(path="backend/raid_member", name="backend_raid_member", assetScope="Backend")
 */
class BackendRaidMemberController extends BaseCrudController {
    protected $model = RaidMember::class;

    protected $crudActions = [
        'delete' => true,
        'create' => false,
        'update' => false,
    ];

    protected $indexPagination = [
        'default' => 50,
        'buttons' => [10, 25, 50, 100, 250],
    ];

    protected $modelProperties = [
        [
            'name'  => 'user',
            'label' => ['key' => 'user', 'default' => 'User'],
            'type'  => CrudDataTypes::CUSTOM,
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'delete' => 'readonly'
            ],
            'renderer' => [
               'custom' => 'Plugins/RaidManagement/Backend/Crud/User.twig'
            ],
        ],
        [
            'name'  => 'raid',
            'label' => ['key' => 'raid', 'default' => 'Raid'],
            'type'  => CrudDataTypes::CUSTOM,
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'delete' => 'readonly'
            ],
            'renderer' => [
                'custom' => 'Plugins/RaidManagement/Backend/Crud/Raid.twig',
            ],
        ],
        [
            'name'  => 'created',
            'label' => ['key' => 'created', 'default' => 'Erstellt'],
            'type'  => CrudDataTypes::DATETIME,
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'delete' => 'readonly'
            ],
        ],
        [
            'name'  => 'role',
            'label' => ['key' => 'role', 'default' => 'Role'],
            'type'  => CrudDataTypes::STRING,
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'delete' => 'readonly'
            ]
        ],

    ];

    protected $indexOrderBy = [
        'raid' => CrudGroupByOrder::ASC,
    ];

    private function listSelectRaid() {
        $raidEntities = Oforge()->DB()->getForgeEntityManager()->getRepository(RaidEvent::class)->findAll();
        $results = [];
        /** @var RaidEvent $raidEntity */
        foreach($raidEntities as $raidEntity) {
            $results[$raidEntity->getId()] = $raidEntity->getDate();
        }

    }
}
