<?php

namespace App\Service\ActionHandler;

use App\Entity\Gate;

class ObstacleActionHandler implements ActionHandlerInterface
{
    private $actionSymbol;

    public function __construct(string $actionSymbol)
    {
        $this->actionSymbol = $actionSymbol;
    }

    public function getActionSymbol(): string
    {
        return $this->actionSymbol;
    }

    public function makeAction(Gate $gate): Gate
    {
        if ($gate->isPaused()) {
            return $gate;
        }

        if ($this->isGateFullCloseOrOpen($gate)) {
            return $gate;
        }

        if ($gate->getMovementAction() === Gate::MOVING_FORWARD) {
            return $gate->setMovementAction(Gate::MOVING_BACKWARD);
        }

        if ($gate->getMovementAction() === Gate::MOVING_BACKWARD) {
            return $gate->setMovementAction(Gate::MOVING_FORWARD);
        }
    }

    private function isGateFullCloseOrOpen(Gate $gate): bool
    {
        if ($gate->getCurrentStep() === $gate->getMaxAvailablePosition()
            || $gate->getCurrentStep() === $gate->getMinAvailablePosition()) {
            return true;
        }

        return false;
    }
}
