<?php

require_once 'vendor/autoload.php';

use App\Entity\Gate;
use App\Entity\GateAction;
use App\Service\MovingManager;
use App\Service\ActionHandler\ButtonActionHandler;
use App\Service\ActionHandler\ObstacleActionHandler;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;
use App\Service\GateActionsManager;
use App\Exception\InvalidStepException;

if (!isset($argv[1])) {
    echo 'Please provide steps' . PHP_EOL;

    return;
}

$gateSteps = str_split($argv[1]);

$constraint = [
    new NotBlank(),
    new Type('string'),
    new Length(['min' => 1, 'max' => 1]),
    new Choice(GateAction::ALL_AVAILABLE_ACTIONS),
];

$gateActionManager = new GateActionsManager(Validation::createValidator(), $constraint);

try {
    $gateActions = $gateActionManager->getGateActions($gateSteps);

} catch (InvalidStepException $exception) {
    echo $exception->getMessage() . PHP_EOL;
    return;
}

$gate = new Gate(
    5,
    0,
    Gate::MOVING_FORWARD,
    0,
    true
);

$movingManager = new MovingManager(
    [
        new ButtonActionHandler(GateAction::BUTTON),
        new ObstacleActionHandler(GateAction::OBSTACLE),
    ]
);

foreach ($gateActions as $gateAction) {
    echo $movingManager->process($gate, $gateAction);
}

echo PHP_EOL;
