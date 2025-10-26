<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Get menu items based on user role.
     */
    public static function getMenuByRole($role)
    {
        $menus = [];

        $menus = match ($role) {
            'admin' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'icon' => 'fa fa-dashboard'
                ],
                [
                    'label' => 'Users',
                    'route' => 'admin.users.index',
                    'icon' => 'fa fa-dashboard'
                ],
                [
                    'label' => 'Airlines',
                    'route' => 'admin.airlines.index',
                    'icon' => 'fa fa-dashboard'
                ],
                [
                    'label' => 'Airports',
                    'route' => 'admin.airports.index',
                    'icon' => 'fa fa-dashboard'
                ],
                // [
                //     'label' => 'Profiles',
                //     'route' => 'admin.profiles.index',
                //     'icon' => 'fa fa-dashboard'
                // ],
            ],
            'maskapai' => [
                // [
                //     'label' => 'Dashboard',
                //     'route' => 'maskapai.dashboard',
                //     'icon' => 'fa fa-dashboard'
                // ],
                [
                    'label' => 'Penerbangan',
                    'route' => 'maskapai.flights.index',
                    'icon' => 'fa fa-dashboard'
                ],
                [
                    'label' => 'Booking',
                    'route' => 'maskapai.bookings.index',
                    'icon' => 'fa fa-book'
                ],
                [
                    'label' => 'Penumpang',
                    'route' => 'maskapai.passengers.index',
                    'icon' => 'fa fa-users'
                ],
                [
                    'label' => 'Profil',
                    'route' => 'maskapai.profile.edit',
                    'icon' => 'fa fa-user'
                ],
            ],
            'user' => [
                [
                    'label' => 'Penerbangan',
                    'route' => 'user.flights.index',
                    'icon' => 'fa fa-plane'
                ],
                [
                    'label' => 'Booking',
                    'route' => 'user.bookings.index',
                    'icon' => 'fa fa-book'
                ],
                [
                    'label' => 'Profil',
                    'route' => 'user.profile.edit',
                    'icon' => 'fa fa-user'
                ],
            ],
            default => [],
        };

        return $menus;
    }

    public static function findProfileMenu(string $role): ?array
    {
        $menus = static::getMenuByRole($role);

        foreach ($menus as $item) {
            // cek berdasarkan nama route yang mengandung 'profile'
            if (!empty($item['route']) && str_contains($item['route'], 'profile')) {
                return $item;
            }

            // kalau punya children, cari di dalamnya juga
            if (!empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    if (!empty($child['route']) && str_contains($child['route'], 'profile')) {
                        return $child;
                    }
                }
            }
        }

        return null;
    }


    /**
     * Helper: check if an item (or its children) match the current route name.
     */
    public static function isActive(array $item, ?string $currentRoute = null): bool
    {
        $currentRoute = $currentRoute ?? (request()->route()?->getName() ?? null);

        if (!empty($item['route']) && $item['route'] === $currentRoute) {
            return true;
        }

        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                if (static::isActive($child, $currentRoute)) {
                    return true;
                }
            }
        }

        return false;
    }
}