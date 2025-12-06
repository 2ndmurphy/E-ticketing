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
                    'route' => 'admin.dashboard.index',
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
                [
                    'label' => 'Dashboard',
                    'route' => 'maskapai.dashboard.index',
                    'icon' => 'fa fa-dashboard'
                ],
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
                    'label' => 'Dashboard',
                    'route' => 'user.dashboard.index',
                    'icon' => 'fa fa-dashboard'
                ],
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

    public static function findProfileMenu(string|array $roleOrMenus): ?array
    {
        // Accept either a role name (string) or an already-resolved menus array.
        // This avoids double lookups when the caller already has the menus.
        $menus = is_string($roleOrMenus) ? static::getMenuByRole($roleOrMenus) : $roleOrMenus;

        if (!is_array($menus)) {
            return null;
        }

        foreach ($menus as $item) {
            // check based on route name containing 'profile' (defensive)
            $route = $item['route'] ?? '';
            if ($route !== '' && str_contains($route, 'profile')) {
                return $item;
            }

            // if it has children, search inside them as well (defensive)
            $children = $item['children'] ?? [];
            if (!empty($children) && is_array($children)) {
                foreach ($children as $child) {
                    $childRoute = $child['route'] ?? '';
                    if ($childRoute !== '' && str_contains($childRoute, 'profile')) {
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

        $children = $item['children'] ?? [];
        if (!empty($children) && is_array($children)) {
            foreach ($children as $child) {
                if (static::isActive($child, $currentRoute)) {
                    return true;
                }
            }
        }

        return false;
    }
}