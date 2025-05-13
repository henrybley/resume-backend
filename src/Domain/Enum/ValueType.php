<?php

namespace App\Domain\Enum;

enum ValueType: string {
    case LEFT = "left";
    case RIGHT = "right";
    case FULL = "full";
}
