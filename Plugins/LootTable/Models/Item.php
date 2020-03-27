<?php

namespace LootTable\Models;

use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Item
 *
 * @package LootTable\Models
 * @ORM\Table(name="item")
 * @ORM\Entity
 */
class Item extends AbstractModel {
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
     * @ORM\Column(name="slot", type="string", nullable=false)
     */
    private $slot;
    /**
     * @var string
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;
    /**
     * @var string
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="raid", type="string", nullable=false)
     */
    private $raid;
    /**
     * @var boolean
     * @ORM\Column(name="classbound", type="boolean", nullable=true)
     */
    private $classbound = false;

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlot() : string {
        return $this->slot;
    }

    /**
     * @param string $slot
     *
     * @return Item
     */
    public function setSlot(string $slot) : Item {
        $this->slot = $slot;

        return $this;
    }

    /**
     * @return string
     */
    public function getType() : string {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Item
     */
    public function setType(string $type) : Item {
        $this->type = $type;

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
     * @return Item
     */
    public function setRaid(string $raid) : Item {
        $this->raid = $raid;

        return $this;
    }

    /**
     * @return bool
     */
    public function isClassbound() : bool {
        return $this->classbound;
    }

    /**
     * @param null|bool $classbound
     *
     * @return Item
     */
    public function setClassbound(?bool $classbound) : Item {
        $this->classbound = $classbound;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Item
     */
    public function setTitle(string $title) : Item {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemNumber() : int {
        return $this->itemNumber;
    }

    /**
     * @param int $itemNumber
     *
     * @return Item
     */
    public function setItemNumber(int $itemNumber) : Item {
        $this->itemNumber = $itemNumber;

        return $this;
    }

}
