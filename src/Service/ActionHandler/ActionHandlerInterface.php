<?php

declare(strict_types=1);

namespace App\Service\ActionHandler;

use App\Entity\Gate;

interface ActionHandlerInterface
{
    public function getActionSymbol(): string;
    public function makeAction(Gate $gate): Gate;
}
