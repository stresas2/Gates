<?php

declare(strict_types=1);

use App\Entity\Gate;
use App\Entity\GateAction;
use App\Exception\InvalidStepException;
use App\Service\ActionHandler\ButtonActionHandler;
use App\Service\ActionHandler\ObstacleActionHandler;
use App\Service\GateActionsManager;
use App\Service\MovingManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validation;

class GatesWorkingTest extends TestCase
{
    private $gateActionManager;
    private $gate;
    private $movingManager;

    protected function setUp(): void
    {
        $constraint = [
            new NotBlank(),
            new Type('string'),
            new Length(['min' => 1, 'max' => 1]),
            new Choice(GateAction::ALL_AVAILABLE_ACTIONS)
        ];

        $this->gateActionManager = new GateActionsManager(Validation::createValidator(), $constraint);

        $this->gate = new Gate(
            5,
            0,
            Gate::MOVING_BACKWARD,
            0,
            false
        );

        $this->movingManager = new MovingManager(
            [
                new ButtonActionHandler(GateAction::BUTTON),
                new ObstacleActionHandler(GateAction::OBSTACLE),
            ]
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAdd(string $input, string $expectResult, bool $failure = false): void
    {
        if ($failure === true) {
            $this->expectException(InvalidStepException::class);
        }

        $gateSteps = str_split($input);
        $gateActions = $this->gateActionManager->getGateActions($gateSteps);

        $result = '';
        foreach ($gateActions as $gateAction) {
            $result .= $this->movingManager->process($this->gate, $gateAction);
        }

        if ($failure === false) {
            $this->assertEquals($result, $expectResult);
        }
    }

    public function dataProvider(): array
    {
        return [
            'test if failure with no valid input' => [
                'input' => '..P...O....AB',
                'output' => '001234321000',
                'failure' => true
            ],
            'test if button was push and after that was detect obstacle' => [
                'input' => '..P...O.....',
                'output' => '001234321000',
            ],
            'test if button was push twice' => [
                'input' => '.P....P....',
                'output' => '01234543210',
            ],
            'test if wrongly was detect obstacle' => [
                'input' => '...O....',
                'output' => '00000000',
            ],
            'test if was paused' => [
                'input' => 'P..P...P..',
                'output' => '1233333455',
            ],
            'test if was paused and detect obstacle' => [
                'input' => 'P..P...O..',
                'output' => '1233333333',
            ],
        ];
    }
}
