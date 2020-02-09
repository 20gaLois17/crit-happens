<?php

namespace LootTable\Models;

use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class LootItem
 *
 * @package LootTable\Models
 * @ORM\Table(name="loot_item")
 * @ORM\Entity
 */
class LootItem extends AbstractModel {
  /**
   * @var int
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;
  /**
   * @var int
   * @ORM\Column(name="item_number", type="integer", nullable=false)
   */
  private $itemNumber;
  /**
   * @var string
   * @ORM\Column(name="item_type", type="string", nullable=false)
   */
  private $itemType;
  /**
   * @var string 
   * @ORM\Column(name="raid", type="string", nullable=true)
   */
  private $raid;

    /**
     * @return int
     */
    public function getItemNumber() : int {
        return $this->itemNumber;
    }

    /**
     * @param int $itemNumber
     *
     * @return LootItem
     */
    public function setItemNumber(int $itemNumber) : LootItem {
        $this->itemNumber = $itemNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemType() : string {
        return $this->itemType;
    }

    /**
     * @param string $itemType
     *
     * @return LootItem
     */
    public function setItemType(string $itemType) : LootItem {
        $this->itemType = $itemType;

        return $this;
    }

    /**
     * @return string
     */
    public function getRaid() : string {
        return $this->raid;
    }

    /**
     * @param string $raid
     *
     * @return LootItem
     */
    public function setRaid(string $raid) : LootItem {
        $this->raid = $raid;

        return $this;
    }

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

}
