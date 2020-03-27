<?php

namespace LootTable\Services;

use Dkp\Services\DkpService;
use Doctrine\DBAL\Platforms\Keywords\PostgreSQL91Keywords;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Models\User;
use LootTable\Models\Item;
use LootTable\Models\LootItem;
use LootTable\Models\LootPreference;
use Noodlehaus\Exception;
use Oforge\Engine\Modules\Core\Abstracts\AbstractDatabaseAccess;
use Oforge\Engine\Modules\Core\Exceptions\ServiceNotFoundException;
use SplFileObject;

class LootManagementService extends AbstractDatabaseAccess {
    public function __construct() {
        parent::__construct([
            'user'       => User::class,
            'item'       => LootItem::class,
            'preference' => LootPreference::class,
            'item_new'   => Item::class,
        ]);
    }

    /**
     * @return array
     * @throws ORMException
     */
    public function listItems() {
        /** @var LootItem[] $itemEntities */
        $itemEntities = $this->repository('item')->findAll();
        $items        = [];
        foreach ($itemEntities as $itemEntity) {
            $items[$itemEntity->getRaid()][$itemEntity->getItemType()][] = $itemEntity->toArray();
        }

        return $items;
    }

    /**
     * @return array
     * @throws ORMException
     */
    public function listPreferencesByUser() {
        /** @var LootPreference[] $preferenceEntities */
        $preferenceEntities = $this->repository('preference')->findAll();
        $preferences        = [];
        foreach ($preferenceEntities as $entity) {
            if ($entity->getUser()->isActive()) {
                $preferences[$entity->getUser()->getid()][] = $entity->getItem()->getId();
            }
        }

        return $preferences;
    }

    /**
     * @return array
     * @throws ORMException
     * @throws ServiceNotFoundException
     */
    public function listPreferencesByItem() {
        /** @var LootPreference[] $preferenceEntities */
        $preferenceEntities = $this->repository('preference')->findAll();
        $preferences        = [];
        foreach ($preferenceEntities as $entity) {
            if ($entity->getUser()->isActive()) {
                $preferences[$entity->getItem()->getId()][] = [
                    "name"   => $entity->getUser()->getEmail(),
                    "class"  => $entity->getUser()->getClass(),
                    "demand" => $entity->getDemand(),
                ];
            }
        }

        return $preferences;
    }

    public function createLookupTable() {
        /** @var DkpService $dkpService */
        $dkpService   = Oforge()->Services()->get('dkp');
        $userEntities = Oforge()->DB()->getForgeEntityManager()->getRepository(User::class)->findBy(['active' => true]);
        $output       = [];
        /** @var User $userEntity */
        foreach ($userEntities as $userEntity) {
            $output[$userEntity->getEmail()] = $dkpService->getUserDkp($userEntity->getId())['total'];
        }

        return $output;
    }

    /**
     * @param int $userId
     * @param int $itemId
     * @param int $demand
     *
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function togglePreference(int $userId, int $itemId, int $demand) {
        $user = $this->repository('user')->find($userId);
        $item = $this->repository('item')->find($itemId);

        if (!isset($user) || !isset($item)) {
            return false;
        }
        $existingPreference = $this->repository('preference')->findOneBy(['user' => $userId, 'item' => $itemId]);
        if (isset($existingPreference)) {
            $this->entityManager()->remove($existingPreference);

        } else {
            $lootPreference = new LootPreference();
            $lootPreference->setItem($item)->setUser($user)->setDemand($demand);
            $this->entityManager()->create($lootPreference);
        }

        return true;
    }

    /**
     * @param $filename
     *
     * @return int
     * @throws ORMException
     */
    public function migrateItems($filename) {
        $splFileObject = new SplFileObject($filename);
        $splFileObject->setCsvControl(",", '"');
        $splFileObject->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::READ_CSV | SplFileObject::DROP_NEW_LINE);

        $rows = [];
        foreach ($splFileObject as $row) {
            // sanitize items
            $rows[] = explode(";", str_replace('"', "", $row[0]));
        }

        /**
         * row[0] => id
         * row[1] => title
         * row[2] => slot
         * row[3] => type
         * row[4] => raid
         */
        // create database entries
        $em = Oforge()->DB()->getForgeEntityManager();
        foreach ($rows as $row) {
            if(!array_key_exists(1,$row)) continue;
            /** @var Item $item */
            $item = new Item();
            $item->setItemNumber((int) $row[0])#
                 ->setTitle($row[1])#
                 ->setSlot($row[2])#
                 ->setType($row[3])#
                 ->setRaid($row[4]);

            $em->create($item);
        }
        return "item entities created";
    }
}
