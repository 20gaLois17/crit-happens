<?php

namespace FrontendUserManagement\Controller\Backend;

use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Oforge\Engine\Modules\CRUD\Controller\Backend\BaseCrudController;
use Oforge\Engine\Modules\CRUD\Enum\CrudDataTypes;
use Oforge\Engine\Modules\CRUD\Enum\CrudFilterComparator;
use Oforge\Engine\Modules\CRUD\Enum\CrudFilterType;
use Oforge\Engine\Modules\CRUD\Enum\CrudGroupByOrder;

/**
 * Class CategoryController
 *
 * @package FrontendUserManagement\Controller\Backend\FrontendUserManagement
 * @EndpointClass(path="/backend/frontendusers", name="backend_frontend_user_management", assetScope="Backend")
 */
class BackendFrontendUserManagementController extends BaseCrudController {
    /** @var string $model */
    protected $model = User::class;
    /** @var array $modelProperties */
    protected $modelProperties = [
        [
            'name'  => 'id',
            'type'  => CrudDataTypes::INT,
            'label' => ['key' => 'plugin_frontend_user_management_property_id', 'default' => 'Id'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'on',
                'update' => 'on',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'email',
            'type'  => CrudDataTypes::EMAIL,
            'label' => ['key' => 'plugin_frontend_user_management_property_email', 'default' => 'Account email'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'on',
                'update' => 'on',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'image_id',
            'type'  => CrudDataTypes::CUSTOM,
            'lable' => ['key' => 'plugin_frontend_user_management_property_profile_image', 'default' => 'Profile image'],
            'crud'     => [
                'index'  => 'off',
                'view'   => 'readonly',
                'create' => 'on',
                'update' => 'on',
                'delete' => 'readonly',
            ],
            'renderer' => [
                'custom' => 'Plugins/FrontendUserManagement/Backend/BackendFrontendUserManagement/CRUD/RenderProfileImage.twig',
            ],
        ],
        [
            'name'  => 'active',
            'type'  => CrudDataTypes::BOOL,
            'label' => [
                'key'     => 'active',
                'default' => [
                    'en' => 'Active',
                    'de' => 'Aktiviert',
                ],
            ],
            'crud'  => [
                'index'  => 'editable',
                'view'   => 'readonly',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'dkp',
            'type'  => CrudDataTypes::INT,
            'label' => [
                'key'     => 'dpk',
                'default' => [
                    'en' => 'DKP',
                    'de' => 'DKP',
                ],
            ],
            'crud'  => [
                'index'  => 'editable',
                'view'   => 'readonly',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'coreRaider',
            'type'  => CrudDataTypes::BOOL,
            'label' => [
                'key'     => 'core_raider',
                'default' => [
                    'en' => 'Core Raider',
                    'de' => 'Stamm-Raider',
                ],
            ],
            'crud'  => [
                'index'  => 'editable',
                'view'   => 'readonly',
                'create' => 'readonly',
                'update' => 'editable',
                'delete' => 'readonly',
            ],
        ],
    ];
    /**
     * @var array $crudActions Keys of 'add|edit|delete'
     */
    protected $crudActions = [
        'index'  => true,
        'create' => true,
        'view'   => true,
        'update' => true,
        'delete' => true,
    ];

    /** @var array $indexFilter */
    protected $indexFilter = [
        'email' => [
            'type'    => CrudFilterType::TEXT,
            'label'   => ['key' => 'plugin_frontend_user_management_filter_email', 'default' => 'Search in email'],
            'compare' => CrudFilterComparator::LIKE,
        ],
        'class'     => [
            'type'    => CrudFilterType::TEXT,
            'label'   => ['key' => 'plugin_frontend_user_management_filter_class', 'default' => 'Search in class'],
            'compare' => CrudFilterComparator::LIKE,
        ],
    ];
    /** @var array $indexOrderBy */
    protected $indexOrderBy = [
        'id' => CrudGroupByOrder::ASC,
    ];

    public function __construct() {
        parent::__construct();
    }
}
