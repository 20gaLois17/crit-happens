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
  private $demand;
}
