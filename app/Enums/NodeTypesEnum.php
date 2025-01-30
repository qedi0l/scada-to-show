<?php

namespace App\Enums;

/**
 * Node Types
 */
enum NodeTypesEnum: int
{
    case VIRTUAL_POINT = 0;
    case SERVICE_NODE = 1;
    case MAIN_NODE = 2;
    case TEXT = 3;
}
