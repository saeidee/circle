<?php

namespace App\Enums;

use Illuminate\Support\Carbon;

/**
 * Class CircuitEnums
 * @package App\Enums
 */
final class CircuitEnums
{
    const OPEN = 0;
    const CLOSE = 1;
    const HALF_OPEN = 2;
    const MAX_ATTEMPT_REACHED = 3;
    const MAX_ATTEMPT_WAIT = Carbon::SECONDS_PER_MINUTE * 5;
}
