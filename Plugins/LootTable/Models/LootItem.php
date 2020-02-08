<?php

namespace LootTable\Models;

use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use Doctrine\ORM\Mapping as ORM;

public class LootItem extends AbstractModel {
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

  public function setItemNumber(int $itemNumber) {
    $this->itemNumber = $itemNumber;
    return $this;
  }
  public function getItemNumber() : int {
    return $this->itemNumber;
  }
}
