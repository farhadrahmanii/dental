<?php

namespace App\Enums;

enum ToothNumber: string
{
    case T1 = '1';
    case T2 = '2';
    case T3 = '3';
    case T4 = '4';
    case T5 = '5';
    case T6 = '6';
    case T7 = '7';
    case T8 = '8';
    case T9 = '9';
    case T10 = '10';
    case T11 = '11';
    case T12 = '12';
    case T13 = '13';
    case T14 = '14';
    case T15 = '15';
    case T16 = '16';
    case T17 = '17';
    case T18 = '18';
    case T19 = '19';
    case T20 = '20';
    case T21 = '21';
    case T22 = '22';
    case T23 = '23';
    case T24 = '24';
    case T25 = '25';
    case T26 = '26';
    case T27 = '27';
    case T28 = '28';
    case T29 = '29';
    case T30 = '30';
    case T31 = '31';
    case T32 = '32';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
