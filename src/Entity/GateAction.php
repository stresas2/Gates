<?php

declare(strict_types = 1);

namespace App\Entity;

class GateAction
{
    const BUTTON = 'P';
    const OBSTACLE = 'O';
    const REGULAR = '.';

    const ALL_AVAILABLE_ACTIONS = [
        self::BUTTON,
        self::OBSTACLE,
        self::REGULAR
    ];

    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
