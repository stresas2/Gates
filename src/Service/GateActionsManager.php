<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\GateAction;
use App\Exception\InvalidStepException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GateActionsManager
{
    private $validator;
    private $constraint;

    public function __construct(
        ValidatorInterface $validator,
        array $constraint
    ) {
        $this->validator = $validator;
        $this->constraint = $constraint;
    }

    /**
     * @param array $gateSteps
     * @return GateAction[]
     * @throws InvalidStepException
     */
    public function getGateActions(array $gateSteps): array
    {
        $gateActions = [];

        foreach ($gateSteps as $gateStep) {
            $this->checkIfStepIsValid($gateStep);
            $gateActions[] = new GateAction($gateStep);
        }

        return $gateActions;
    }

    /**
     * @throws InvalidStepException
     */
    private function checkIfStepIsValid(string $gateStep)
    {
        $violations = $this->validator->validate($gateStep, $this->constraint);

        if (count($violations) > 0) {
            throw new InvalidStepException('Sorry this step is not valid -> ' . $gateStep);
        }
    }
}
