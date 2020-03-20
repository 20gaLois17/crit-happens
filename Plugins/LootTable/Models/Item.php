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

    private $typeKey;

    private $raidKey;

    private $classbound;


}
