<?php

namespace App\Enums;

enum ImportType: string
{
    case STATIC = 'static';
    case DYNAMIC = 'dynamic';
}
