<?php

namespace RaidManagement\Services;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Abstracts\AbstractDatabaseAccess;
use RaidManagement\Models\RaidEvent;
use RaidManagement\Models\RaidMember;

class RaidManagementService extends AbstractDatabaseAccess {
    public function __construct() {
        parent::__construct([
            'raid'        => RaidEvent::class,
            'raid_member' => RaidMember::class,
        ]);
    }

    /**
     * List all active
     *
     * @return array
     * @throws ORMException
     */
    public function listActiveRaids() {
        $raidEntities = $this->repository('raid')->findBy(['active' => true], ['date' => 'ASC']);
        $raids        = [];
        /** @var RaidEvent $raidEntity */
        foreach ($raidEntities as $raidEntity) {
            $raids[] = $raidEntity->toArray();
        }

        return $raids;
    }

    /**
     * @param User $user
     *
     * @return array
     * @throws ORMException
     */
    public function listUserParticipation(User $user) {
        $raidMemberEntities = $this->repository('raid_member')->findBy(['user' => $user]);
        $userRaids          = [];

        /** @var RaidMember $raidMemberEntity */
        foreach ($raidMemberEntities as $raidMemberEntity) {
            $userRaids[$raidMemberEntity->getRaid()->getId()] = ['role' => $raidMemberEntity->getRole()];
        }

        return $userRaids;
    }

    /**
     * @param User $user
     * @param RaidEvent $raid
     * @param array $data
     *
     * @return int
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function toggleRaidSubscription(User $user, RaidEvent $raid, array $data) {
        $raidMember = $this->repository('raid_member')->findOneBy(['user' => $user, 'raid' => $raid]);
        if (isset($raidMember)) {
            $this->entityManager()->remove($raidMember);

            return 0;

        } else {
            $raidMember = new RaidMember();
            $raidMember->setUser($user)->setRaid($raid)->setRole($data['role']);
            $this->entityManager()->create($raidMember);
        }

        return 1;
    }

    /**
     * @param RaidEvent $raid
     *
     * @return array
     * @throws ORMException
     */
    public function listRaidMembers(RaidEvent $raid) {
        $raidMemberEntities = $this->repository('raid_member')->findBy(['raid' => $raid]);
        $users              = [];
        /** @var RaidMember $raidMemberEntity */
        foreach ($raidMemberEntities as $raidMemberEntity) {
            $users[] = $raidMemberEntity->getUser()->toArray(2, ['guid', 'password', 'createdAt', 'updatedAt', 'sidebar_navigation']);
        }

        return $users;
    }

    /**
     * @param RaidEvent $raid
     *
     * @return array
     * @throws ORMException
     */
    public function countRoles(RaidEvent $raid) {
        $roles = [];
        $roles['tank'] = $this->repository('raid_member')->count(['raid' => $raid, 'role' => 'tank']);
        $roles['healer'] = $this->repository('raid_member')->count(['raid' => $raid, 'role' => 'healer']);
        $roles['dps'] = $this->repository('raid_member')->count(['raid' => $raid, 'role' => 'dps']);

        return $roles;
    }

}
