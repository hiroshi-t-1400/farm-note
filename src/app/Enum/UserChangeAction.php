<?php

namespace App\Enum;

enum UserChangeAction: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DISABLE = 'disable';
}
