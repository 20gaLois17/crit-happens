<?php

namespace LootTable\Models;

use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class LootPreference
 *
 * @package LootTable\Models
 * @ORM\Table(name="loot_preference")
 * @ORM\Entity
 */
class LootPreference extends AbstractModel {
  /**
   * @var int
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

    /**
     * @ORM\ManyToOne(targetEntity="FrontendUserManagement\Models\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
  private $user;

    /**
     * @ORM\ManyToOne(targetEntity="LootItem")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
  private $item;

    /**
     * @var
     * @ORM\Column(name="demand", type="integer", nullable=false)
     */
  private $demand = 1;

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return LootPreference
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * @param mixed $item
     *
     * @return LootPreference
     */
    public function setItem($item) {
        $this->item = $item;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDemand() {
        return $this->demand;
    }

    /**
     * @param mixed $demand
     *
     * @return LootPreference
     */
    public function setDemand($demand) {
        $this->demand = $demand;

        return $this;
    }


}
