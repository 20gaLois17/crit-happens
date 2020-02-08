<?php

namespace LootTable\Models;

use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use Doctrine\ORM\Mapping as ORM;

public class LootPreference extends AbstractModel {
  /**
   * @var int
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  private $user;

  private $lootItem;

  private $degree;
}
