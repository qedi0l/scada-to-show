<?php

namespace App\Models\Types;

enum NodeTypeType: string
{
    case Default = 'default';
    case Transparent = 'transparent';
    case Link = 'link';
}
