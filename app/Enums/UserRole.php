<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Manager = 'gestor';
    case Organizer = 'organizador';
    case Client = 'cliente';

    public function isAdmin(): bool
    {
        return in_array($this, [
            self::SuperAdmin,
            self::Admin,
            self::Manager,
            self::Organizer
        ]);
    }
}
