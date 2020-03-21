<?php

namespace Dkp\Services;

use DateTime;
use Dkp\Models\DkpEntry;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Abstracts\AbstractDatabaseAccess;
use RaidManagement\Models\RaidEvent;
use RaidManagement\Models\RaidMember;

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
        $entryEntities = $this->repository('default')->findBy(['user' => $userId], ['createdAt' => 'DESC']);

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
                     ->setDescription('Dkp Stand');
            $em->create($dkpEntry, false);
        }
        $em->flush();
    }

    /**
     * @param $raid_id
     * @param $amount
     *
     * @throws ORMException
     * @throws \Exception
     */
    public function grantAttendanceDkp($raid_id, $amount) {
        $em = Oforge()->DB()->getForgeEntityManager();
        /** @var RaidEvent $raid */
        $raid = $em->getRepository(RaidEvent::class)->find($raid_id);
        if ($raid->getDate() > new DateTime('now')) {
            die("this raid is not over yet \n");
        }
        $raidMemberEntities = $em->getRepository(RaidMember::class)->findBy(['raid' => $raid_id]);
        if(sizeof($raidMemberEntities) > 0) {
            /** @var RaidMember $raidMemberEntity */
            foreach ($raidMemberEntities as $raidMemberEntity) {
                $raidMember = $raidMemberEntity->getUser();
                $dkpEntry = new DkpEntry();
                $dkpEntry->setUser($raidMember)
                         ->setValue($amount)
                         ->setDescription('Teilnahme')
                         ->setRaid($raid);
                $em->create($dkpEntry, false);
            }
            $em->flush();
            print("dkp has been granted \n");
            return;
        } else {
            die("no member in this raid");
        }
    }
}
