<?php

namespace RaidManagement\Models;

use DateTime;
use Exception;
use FrontendUserManagement\Models\User;
use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class RaidMember
 * @ORM\Table(name="raid_member")
 * @ORM\Entity
 */
class RaidMember extends AbstractModel {

    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="FrontendUserManagement\Models\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var RaidEvent
     * @ORM\ManyToOne(targetEntity="RaidEvent")
     * @ORM\JoinColumn(name="raid_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $raid;

    /**
     * @var string
     * @ORM\Column(name="role", type="string", nullable=true)
     */
    private $role;

    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $created;

    /**
     * 0 => default value
     * 1 => member has signed in 24h before raid start
     * 2 => guarantee participation
     * @var Integer
     * @ORM\Column(name="member_state", type="integer", nullable=true)
     */
    private $memberState;

    /**
     * RaidMember constructor.
     *
     * @throws Exception
     */
    public function __construct() {
        $this->created     = new DateTime('now');
        $this->memberState = 0;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param User $user
     *
     * @return RaidMember
     */
    public function setUser(User $user) {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param RaidEvent $raid
     *
     * @return RaidMember
     */
    public function setRaid(RaidEvent $raid) {
        $this->raid = $raid;

        return $this;
    }

    /**
     * @return RaidEvent
     */
    public function getRaid() {
        return $this->raid;
    }

    /**
     * @param $role
     *
     * @return RaidMember
     */
    public function setRole($role) {
        $this->role = $role;

        return $this->role;
    }

    /**
     * @return string
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @return DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param $memberState
     *
     * @return RaidMember
     */
    public function setMemberState(int $memberState) {
      $this->memberState = $memberState;

      return $this;
    }

    /**
     * @return int
     */
    public function getMemberState() : int {
      return $this->memberState;
    }
}
