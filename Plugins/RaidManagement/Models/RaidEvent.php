<?php

namespace RaidManagement\Models;

use DateTime;
use DateTimeImmutable;
use Oforge\Engine\Modules\Core\Abstracts\AbstractModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class RaidEvent
 *
 * @package RaidManagement\Models
 *
 * @ORM\Table(name="raid_events")
 * @ORM\Entity
 */
class RaidEvent extends AbstractModel {

    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var DateTime
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    private $deadline;

    /**
     * @var bool
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /** @var int
     * @ORM\Column(name="icon_key", type="integer", nullable=true)
     */
    private $icon;

    public function __construct() {
        $this->deadline = new DateTime();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return RaidEvent
     */
    public function setTitle(string $title) {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $description
     *
     * @return RaidEvent
     */
    public function setDescription(string $description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param DateTime $date
     *
     * @return RaidEvent
     */
    public function setDate(DateTime $date) {
        $this->date = $date;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param DateTime $deadline
     *
     * @return RaidEvent
     */
    public function setDeadline(DateTime $deadline) {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDeadline() {
        return $this->deadline;
    }

    /**
     * @param bool $active
     *
     * @return RaidEvent
     */
    public function setActive(bool $active) {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * @param int $icon
     *
     * @return RaidEvent
     */
    public function setIcon(int $icon) {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return int
     */
    public function getIcon() {
        return $this->icon;
    }

}
