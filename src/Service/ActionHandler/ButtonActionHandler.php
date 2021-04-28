<?php

namespace App\Service\ActionHandler;

use App\Entity\Gate;

class ButtonActionHandler implements ActionHandlerInterface
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
        if ($gate->getCurrentStep() === $gate->getMaxAvailablePosition() && $gate->isPaused() === false) {
            return $gate->setMovementAction(Gate::MOVING_BACKWARD);
        }

        if ($gate->getCurrentStep() === $gate->getMinAvailablePosition() && $gate->isPaused() === false) {
            return $gate->setMovementAction(Gate::MOVING_FORWARD);
        }

        return $gate->setPaused(!($gate->isPaused() === true));
    }
}
