<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case VIEW = 'view';
    case CREATE = 'create';
    case EDIT = 'edit';
    case DELETE = 'delete';
    case NONE = 'none';
}
