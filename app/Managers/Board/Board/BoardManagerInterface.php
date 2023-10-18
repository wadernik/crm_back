<?php

declare(strict_types=1);

namespace App\Managers\Board\Board;

interface BoardManagerInterface extends BoardCreatorInterface, BoardUpdaterInterface, BoardDeleterInterface
{
}