<?php declare(strict_types=1);

namespace RichId\MaintenanceBundle\Model;

class MaintenanceModel
{
    /** @var bool */
    private $isLocked;

    public function __construct(bool $isLocked)
    {
        $this->isLocked = $isLocked;
    }

    public function setIsLocked(bool $isLocked): self
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    public function isLocked(): bool
    {
        return $this->isLocked;
    }
}
