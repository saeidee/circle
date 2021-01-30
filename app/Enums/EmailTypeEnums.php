<?php

namespace App\Enums;

/**
 * Class EmailTypeEnums
 * @package App\Enums
 */
final class EmailTypeEnums
{
    const TEXT = 'text/plain';
    const HTML = 'text/html';
    const ALL = [self::TEXT, self::HTML];
}
