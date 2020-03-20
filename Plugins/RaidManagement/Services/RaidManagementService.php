<?php

namespace RaidManagement\Services;

use DateTime;
use Dkp\Services\DkpService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Abstracts\AbstractDatabaseAccess;
use Oforge\Engine\Modules\Core\Exceptions\ServiceNotFoundException;
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
     * @throws ServiceNotFoundException
     */
    public function listRaidMembers(RaidEvent $raid) {
        /** @var DkpService $dkpService */
        $dkpService = Oforge()->Services()->get('dkp');
        $raidMemberEntities = $this->repository('raid_member')->findBy(['raid' => $raid]);
        $users              = [];
        /** @var RaidMember $raidMemberEntity */
        foreach ($raidMemberEntities as $raidMemberEntity) {
            $raidMember                 = $raidMemberEntity->getUser()->toArray(2, ['guid', 'password', 'createdAt', 'updatedAt', 'sidebar_navigation', 'dkp']);
            $raidMember['role']         = $raidMemberEntity->getRole();
            $raidMember['member_state'] = $raidMemberEntity->getMemberState();
            $raidMember['dkp']          = $dkpService->getUserDkp($raidMember['id'])['total'];
            $users[]                    = $raidMember;
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
        $roles           = [];
        $roles['tank']   = $this->repository('raid_member')->count(['raid' => $raid, 'role' => 'tank']);
        $roles['healer'] = $this->repository('raid_member')->count(['raid' => $raid, 'role' => 'healer']);
        $roles['dps']    = $this->repository('raid_member')->count(['raid' => $raid, 'role' => 'dps']);

        return $roles;
    }

    /**
     * @param $raid_id
     * @param $amount
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function grantAttendanceDkp($raid_id, $amount) {
        $em = Oforge()->DB()->getForgeEntityManager();
        /** @var RaidEvent $raid */
        $raid = $em->getRepository(RaidEvent::class)->find($raid_id);
        if ($raid->getDate() > new DateTime('now')) {
            die("this raid is not over yet \n");
        }
        $raidMemberEntities = $em->getRepository(RaidMember::class)->findBy(['raid' => $raid_id]);

        /** @var RaidMember $raidMemberEntity */
        foreach ($raidMemberEntities as $raidMemberEntity) {
            $raidMember = $raidMemberEntity->getUser();
            $currentDkp = $raidMember->getDkp();
            $raidMember->setDkp($currentDkp + $amount);
            $em->update($raidMember);
            //$em->remove($raidMemberEntity);
        }
        print("dkp has been granted \n");
    }

    /**
     * @param string $date
     *
     * @return string
     * @throws ORMException
     * @throws \Exception
     */
    public function setMemberState($date = "now") {
        /** @var RaidEvent $raidEntities */
        $raidEntities = $this->repository('raid')->findBy(["active" => true]);
        $raid         = null;
        $now          = new DateTime($date);
        if (!empty($raidEntities)) {
            /** @var RaidEvent $raidEntity */
            foreach ($raidEntities as $raidEntity) {
                $diff    = $now->diff($raidEntity->getDate());
                if ($diff->invert == 1) {
                    continue;
                }
                if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0) {
                    $raid = $raidEntity;
                    break;
                }
            }
            if (isset($raid)) {
                /** @var RaidMember[] $raidMemberEntities */
                $raidMemberEntities = $this->repository('raid_member')->findBy(["raid" => $raid->getId()]);
                if (!empty($raidMemberEntities)) {
                    // count the members
                    if (count($raidMemberEntities) <= 40) {
                        /** @var RaidMember $raidMemberEntity */
                        foreach ($raidMemberEntities as $raidMemberEntity) {
                            $raidMemberEntity->setMemberState(2);
                            $this->entityManager()->update($raidMemberEntity, true);
                        }
                    } else {
                        /** @var RaidMember $raidMemberEntity */
                        foreach ($raidMemberEntities as $raidMemberEntity) {
                            /** @var User $user */
                            $user = Oforge()->DB()->getForgeEntityManager()->getRepository(User::class)->find($raidMemberEntity->getUser());
                            if ($user->isCoreRaider()) {
                                $raidMemberEntity->setMemberState(2);
                                $this->entityManager()->update($raidMemberEntity, true);
                            } else {
                                $raidMemberEntity->setMemberState(1);
                                $this->entityManager()->update($raidMemberEntity, true);
                            }
                        }
                    }
                }
            }
        }
        return "SetMemberState Method ran successfully \n";
    }
}
