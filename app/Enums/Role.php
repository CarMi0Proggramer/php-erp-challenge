<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case OPERATOR = 'operator';
    case SELLER = 'seller';
    case ACCOUNTANT = 'accountant';
}
