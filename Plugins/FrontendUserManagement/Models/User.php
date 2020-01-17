<?php

namespace FrontendUserManagement\Models;

use Doctrine\ORM\Mapping as ORM;
use Oforge\Engine\Modules\Auth\Models\User\BaseUser;
use Oforge\Engine\Modules\Core\Helper\SessionHelper;
use PhpParser\Builder\Class_;

/**
 * @ORM\Entity
 * @ORM\Table(name="frontend_user_management_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser {
    /**
     * @var string $guid
     * @ORM\Column(name="guid", type="guid", nullable=true)
     */
    private $guid;
    /**
     * @var string $class
     * @ORM\Column(name="class", type="string", nullable=true)
     */
    private $class;
    /**
     * @var int $dpk
     * @ORM\Column(name="dkp", type="integer", nullable=true)
     */
    private $dkp;

    /**
     * @var bool $coreRaider
     * @ORM\Column(name="core_raider", type="boolean", nullable=true)
     */
    private $coreRaider;

    public function __construct() {
        parent::__construct();
        $this->dkp = 0;
        $this->core_raider = false;
    }
    /** @ORM\PrePersist */
    public function updatedGuid() : void {
        $newGuid = SessionHelper::generateGuid();
        $this->setGuid($newGuid);
    }
    /**
     * @return string
     */
    public function getGuid() : string {
        return $this->guid;
    }
    /**
     * @param string $guid
     *
     * @return User
     */
    public function setGuid(string $guid) : User {
        $this->guid = $guid;

        return $this;
    }
    /**
     * @return string
     */
    public function getClass() : string {
        return $this->class;
    }
    /**
     * @param string $class
     * @return User
     */
    public function setClass(string $class) {
        $this->class = $class;

        return $this;
    }
    /**
     * @param int $dkp
     * @return User
     */
    public function setDkp(int $dkp) {
        $this->dkp=$dkp;

        return $this;
    }
    /**
     * @return int|null
     */
    public function getDkp() : ?int {
        return $this->dkp;
    }
    /**
     * @param boolean $coreRaider
     * @return User
     */
    public function setCoreRaider(boolean $coreRaider) {
      $this->coreRaider = $coreRaider;

      return $this;
    }

    public function isCoreRaider() : ?boolean {
      return $this->coreRaider;
    }

}
