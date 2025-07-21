<?php

namespace App\Enums;

/**
 * Enum representing user roles in the system.
 *
 * This enum defines the different types of users that can access the application,
 * along with helper methods to list all available roles and get human-readable labels.
 *
 * @method static UserRole ADMIN() Admin user with full system access
 * @method static UserRole MANAGER() Manager user role
 * @method static UserRole USER() Standard user role
 * @method static UserRole GUEST() Guest user role with limited access
 * @method static array all() Returns an array of all available user roles
 * @method string label() Returns a human-readable label for the role
 */
enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case USER = 'user';
    case GUEST = 'guest';

    public static function all(): array
    {
        return [
            self::ADMIN,
            self::MANAGER,
            self::USER,
            self::GUEST,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::MANAGER => 'Manager',
            self::USER => 'User',
            self::GUEST => 'Guest',
        };
    }
}
