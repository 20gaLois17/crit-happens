<?php

namespace Dkp\Controller\Backend;

use Dkp\Models\DkpEntry;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Models\User;
use FrontendUserManagement\Services\UserService;
use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Oforge\Engine\Modules\Core\Exceptions\NotFoundException;
use Oforge\Engine\Modules\Core\Exceptions\ServiceNotFoundException;
use Oforge\Engine\Modules\Core\Forge\ForgeEntityManager;
use Oforge\Engine\Modules\CRUD\Controller\Backend\BaseCrudController;
use Oforge\Engine\Modules\CRUD\Enum\CrudDataTypes;
use Oforge\Engine\Modules\CRUD\Enum\CrudFilterType;
use Oforge\Engine\Modules\I18n\Helper\I18N;
use RaidManagement\Models\RaidEvent;
use RaidManagement\Services\RaidManagementService;

/**
 * Class BackendDkpController
 *
 * @package Dkp\Controller\Backen
 * @EndpointClass(path="/backend/dkp", name="backend_dkp_controller", assetScope="Backend")
 */
class BackendDkpController extends BaseCrudController {

    protected $model = DkpEntry::class;

    protected $modelProperties = [
        [
            'name'  => 'user',
            'type'  => CrudDataTypes::SELECT,
            'label' => ['key' => 'user', 'default' => 'Nutzer'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'editable',
                'update' => 'readonly',
                'delete' => 'readonly',
            ],
            'list'  => 'getSelectUsers',
        ],
        [
            'name'  => 'raid',
            'type'  => CrudDataTypes::SELECT,
            'label' => ['key' => 'raid', 'default' => 'Raid'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'editable',
                'update' => 'readonly',
                'delete' => 'readonly',
            ],
            'list'  => 'getSelectRaids',
        ],
        [
            'name'  => 'item',
            'type'  => CrudDataTypes::SELECT,
            'label' => ['key' => 'item', 'default' => 'Item'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'editable',
                'update' => 'readonly',
                'delete' => 'readonly',
            ],
            'list'  => 'getSelectItems',
        ],
        [
            'name'  => 'value',
            'type'  => CrudDataTypes::INT,
            'label' => ['key' => 'value', 'default' => 'Wert'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'editable',
                'update' => 'editable',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'description',
            'type'  => CrudDataTypes::STRING,
            'label' => ['key' => 'description', 'default' => 'Beschreibung'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'editable',
                'update' => 'readonly',
                'delete' => 'readonly',
            ],
        ],
        [
            'name'  => 'createdAt',
            'type'  => CrudDataTypes::DATETIME,
            'label' => ['key' => 'createdAt', 'default' => 'Erstellt'],
            'crud'  => [
                'index'  => 'readonly',
                'view'   => 'readonly',
                'create' => 'readonly',
                'update' => 'readonly',
                'delete' => 'readonly',
            ],
        ],
    ];

    protected $indexFilter = [
        'user' => [
            'type'  => CrudFilterType::SELECT,
            'label' => ['key' => 'name', 'default' => 'Name'],
            'list'  => 'getSelectUsers',
        ],
    ];

    protected $indexPagination = [
        'default' => 50,
        'buttons' => [50, 100, 200, 400],
    ];

    /**
     * @return array
     * @throws ORMException
     * @throws ServiceNotFoundException
     */
    protected function getSelectUsers() : array {
        /** @var UserService $userService */
        $userService = Oforge()->Services()->get('frontend.user.management.user');

        $results      = [];
        $userEntities = $userService->repository()->findBy(['active' => true], ['email' => 'ASC']);
        /** @var User $userEntity */
        foreach ($userEntities as $userEntity) {
            $results[$userEntity->getId()] = $userEntity->getEmail();
        }

        return $results;
    }

    /**
     * @return array
     * @throws ORMException
     * @throws ServiceNotFoundException
     */
    protected function getSelectRaids() : array {
        /** @var RaidManagementService $raidService */
        $raidService = Oforge()->Services()->get('raid.management');

        $results      = [];
        $raidEntities = $raidService->repository('raid')->findBy(['active' => true]);

        /** @var RaidEvent[] $raidEntities */
        foreach ($raidEntities as $raidEntity) {
            $results[$raidEntity->getId()] = $raidEntity->getId();
        }

        return $results;
    }

    protected function prepareItemDataArray(?AbstractModel $entity, string $crudAction) : array {
        $itemData = parent::prepareItemDataArray($entity, $crudAction);
        if (isset($itemData['user']['id'])) {
            $itemData['user'] = $itemData['user']['id'];
        }
        if (isset($itemData['raid']['id'])) {
            $itemData['raid'] = $itemData['raid']['id'];
        }

        return $itemData;
    }

    protected function convertData(array $data, string $crudAction) : array {
        /** @var ForgeEntityManager $entityManager */
        $entityManager = Oforge()->DB()->getForgeEntityManager();

        $userId = $data['user'];
        $raidId = $data['raid'];
        /** @var User|null $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        if (!isset($user)) {
            throw new NotFoundException(sprintf(#
                I18N::translate('plugin_dkp_user_not_found', [
                    'en' => 'User with ID "%s" not found.',
                    'de' => 'Nutzer mit der ID "%s" wurde nicht gefunden.',
                ]),#
                $userId)#
            );
        }
        /** @var RaidEvent|null $raid */
        $raid = $entityManager->getRepository(RaidEvent::class)->findOneBy(['id' => $raidId]);
        if (!isset($raid)) {
            throw new NotFoundException(sprintf(#
                I18N::translate('plugin_dkp_raid_not_found', [
                    'en' => 'Raid with ID "%s" not found.',
                    'de' => 'Raid mit der ID "%s" wurde nicht gefunden.',
                ]),#
                $raid)#
            );
        }
        $data['user'] = $user;
        $data['raid'] = $raid;

        return parent::convertData($data, $crudAction);
    }

}
