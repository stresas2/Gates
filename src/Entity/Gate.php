<?php

declare(strict_types=1);

namespace App\Entity;

class Gate
{
    const MOVING_FORWARD = 'moving_forward';
    const MOVING_BACKWARD = 'moving_backward';

    private $maxAvailablePosition;
    private $minAvailablePosition;
    private $movementAction;
    private $currentStep;
    private $paused;

    public function __construct(
        int $maxAvailablePosition,
        int $minAvailablePosition,
        string $movementAction,
        int $currentStep,
        bool $paused
    ) {
        $this->maxAvailablePosition = $maxAvailablePosition;
        $this->minAvailablePosition = $minAvailablePosition;
        $this->movementAction = $movementAction;
        $this->currentStep = $currentStep;
        $this->paused = $paused;
    }

    public function setMovementAction(string $movementAction): self
    {
        $this->movementAction = $movementAction;

        return $this;
    }

    public function getMovementAction(): string
    {
        return $this->movementAction;
    }

    public function getCurrentStep(): int
    {
        return $this->currentStep;
    }

    public function setCurrentStep(int $currentPosition): self
    {
        $this->currentStep = $currentPosition;

        return $this;
    }

    public function isPaused(): bool
    {
        return $this->paused;
    }

    public function setPaused(bool $paused): self
    {
        $this->paused = $paused;

        return $this;
    }

    public function getMaxAvailablePosition(): int
    {
        return $this->maxAvailablePosition;
    }

    public function getMinAvailablePosition(): int
    {
        return $this->minAvailablePosition;
    }
}
