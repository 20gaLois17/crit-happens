<?php

namespace Dkp\Models;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use FrontendUserManagement\Models\User;
use LootTable\Models\Item;
use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use RaidManagement\Models\RaidEvent;

/**
 * Class DkpEntry
 *
 * @package Dkp\Models
 * @ORM\Table(name="dkp_entry")
 * @ORM\Entity
 */
class DkpEntry extends AbstractModel {
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="FrontendUserManagement\Models\User")
     */
    private $user;
    /**
     * @var RaidEvent
     * @ORM\ManyToOne(targetEntity="RaidManagement\Models\RaidEvent")
     */
    private $raid;
    /**
     * @var Item
     * @ORM\ManyToOne(targetEntity="LootTable\Models\Item")
     */
    private $item;
    /**
     * @var int
     * @ORM\Column(name="value", type="integer", nullable=false)
     */
    private $value;
    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    public function __construct() {
        $this->createdAt = new DateTime('now');
    }

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return DkpEntry
     */
    public function setId(int $id) : DkpEntry {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser() : User {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return DkpEntry
     */
    public function setUser(User $user) : DkpEntry {
        $this->user = $user;

        return $this;
    }

    /**
     * @return null|RaidEvent
     */
    public function getRaid() : ?RaidEvent {
        return $this->raid;
    }

    /**
     * @param null|RaidEvent $raid
     *
     * @return DkpEntry
     */
    public function setRaid(?RaidEvent $raid) : DkpEntry {
        $this->raid = $raid;

        return $this;
    }

    /**
     * @return Item|null
     */
    public function getItem() : ?Item {
        return $this->item;
    }

    /**
     * @param Item $item
     *
     * @return DkpEntry
     */
    public function setItem(?Item $item) : DkpEntry {
        $this->item = $item;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue() : int {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @return DkpEntry
     */
    public function setValue(int $value) : DkpEntry {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() : ?string {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return DkpEntry
     */
    public function setDescription(string $description) : DkpEntry {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt() : DateTime {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return DkpEntry
     */
    public function setCreatedAt(DateTime $createdAt) : DkpEntry {
        $this->createdAt = $createdAt;

        return $this;
    }
}
