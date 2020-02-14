<?php

namespace LootTable\Services;

use FrontendUserManagement\Models\User;
use LootTable\Models\LootItem;
use LootTable\Models\LootPreference;
use Oforge\Engine\Modules\Core\Abstracts\AbstractDatabaseAccess;

class LootManagementService extends AbstractDatabaseAccess {
    public function __construct() {
        parent::__construct([
            'user'       => User::class,
            'item'       => LootItem::class,
            'preference' => LootPreference::class,
        ]);
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function listItems() {
        /** @var LootItem[] $itemEntities */
        $itemEntities = $this->repository('item')->findAll();
        $items = [];
        foreach($itemEntities as $itemEntity) {
            $items[$itemEntity->getItemType()][] = $itemEntity->toArray();
        }
        return $items;
    }
}
