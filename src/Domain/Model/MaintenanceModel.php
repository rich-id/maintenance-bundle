<?php

declare(strict_types=1);

namespace RichId\MaintenanceBundle\Domain\Model;

class MaintenanceModel
{
    /** @var bool */
    private $isClosed;

    public function __construct(bool $isClosed)
    {
        $this->isClosed = $isClosed;
    }

    public function setIsClosed(bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    public function isClosed(): bool
    {
        return $this->isClosed;
    }
}
