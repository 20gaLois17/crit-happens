<?php

namespace Dkp\Services;

use Dkp\Models\DkpEntry;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Abstracts\AbstractDatabaseAccess;

/**
 * Class DkpService
 *
 * @package Dkp\Services
 */
class DkpService extends AbstractDatabaseAccess {
    public function __construct() {
        parent::__construct([
            'default' => DkpEntry::class,
        ]);
    }

    /**
     * @param int $userId
     *
     * @return array
     * @throws ORMException
     */
    public function getDkpHistory(int $userId) {
        $entryEntities = $this->repository('default')->findBy(['user' => $userId], ['createdAt' => 'ASC']);

        $output = [];
        foreach ($entryEntities as $entryEntity) {
            $output[] = $entryEntity->toArray(2, ['user']);
        }
        return $output;
    }

    public function getUserDkp(int $userId) {
        $qb = $this->entityManager()->createQueryBuilder();
        $qb->select('Sum(e.value) as total')
            ->from('Dkp\Models\DkpEntry', 'e')
            ->setParameter('user', $userId)
            ->where('e.user = :user');
        $q = $qb->getQuery();
        $result = $q->execute();
        return $result[0];
    }

    /**
     * @throws ORMException
     */
    public function migrateDkp() {
        $em = Oforge()->DB()->getForgeEntityManager();
        $userEntities = $em->getRepository(User::class)->findAll();
        /** @var User $userEntity */
        foreach ($userEntities as $userEntity) {
            $dkpEntry = new DkpEntry();
            $dkpEntry->setUser($userEntity)
                     ->setValue($userEntity->getDkp())
                     ->setDescription('Aktueller Stand');
            $em->create($dkpEntry, false);
        }
        $em->flush();
    }
}
