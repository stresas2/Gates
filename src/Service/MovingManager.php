<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\GateAction;
use App\Entity\Gate;
use App\Service\ActionHandler\ActionHandlerInterface;

class MovingManager
{
    private $actions;

    /**
     * @param ActionHandlerInterface[] $actions
     */
    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function process(Gate $gate, GateAction $gateAction): int
    {
        foreach ($this->actions as $action) {
            if ($action->getActionSymbol() === $gateAction->getType()) {
                $action->makeAction($gate);
            }
        }

        $currentStep = $gate->getCurrentStep();

        if ($gate->isPaused()) {
            return $currentStep;
        }

        if ($this->isGateInMaxOrMinPosition($gate)) {
            return $currentStep;
        }

        if ($gate->getMovementAction() === Gate::MOVING_FORWARD) {
            $gate->setCurrentStep($currentStep + 1);
        }

        if ($gate->getMovementAction() === Gate::MOVING_BACKWARD) {
            $gate->setCurrentStep($currentStep - 1);
        }

        return $gate->getCurrentStep();
    }

    private function isGateInMaxOrMinPosition(Gate $gate): bool
    {
        if ($gate->getCurrentStep() === $gate->getMaxAvailablePosition()
            && $gate->getMovementAction() === Gate::MOVING_FORWARD) {
            return true;
        }

        if ($gate->getCurrentStep() === $gate->getMinAvailablePosition()
            && $gate->getMovementAction() === Gate::MOVING_BACKWARD) {
            return true;
        }

        return false;
    }
}
