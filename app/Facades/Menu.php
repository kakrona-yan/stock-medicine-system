<?php

namespace App\Facades;

use Illuminate\Support\Facades\Session;

class Menu
{
    function RoleType($routeName)
    {
        $roleType = false;
        if (\Gate::forUser(\Auth::user())->allows($routeName, \Auth::user())) {
            return $roleType = true;
        }
    }
    /*
     * get menu items
     */
    public function getMenus()
    {
        return [
            [
                // dashboard management
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'label' => __('menu.dashboard'),
                'role_type' => $this->RoleType('dashboard'),
                'sub_menu' => null
            ],
            [
                // user management
                'route' => 'user.index',
                'icon' => 'fas fa-user',
                'label' => __('menu.user'),
                'role_type' => $this->RoleType('user.index'),
                'sub_menu' => null
            ],
            // [
            //     // staff management
            //     'route' => 'staff.index',
            //     'icon' => 'fas fa-user',
            //     'label' => __('menu.staff'),
            //     'role_type' => $this->RoleType('staff.index'),
            //     'sub_menu' => null
            // ],
            // [
            //     // category management
            //     'route' => 'category.index',
            //     'icon' => 'fas fa-tags',
            //     'label' => __('menu.category'),
            //     'role_type' => $this->RoleType('category.index'),
            //     'sub_menu' => null
            // ],
            [
                // Product Management
                'route' => 'product.index',
                'icon' => 'fas fa-laptop-medical',
                'label' => __('menu.product'),
                'role_type' => $this->RoleType('product.index'),
                'sub_menu' => null
            ],
            [
                // customer management
                'route' => 'customer.index',
                'icon' => 'fas fa-user',
                'label' => __('menu.customer'),
                'role_type' => $this->RoleType('customer.index'),
                'sub_menu' => null
            ],
            [
                // customer owed management
                'route' => 'customer_owed.index',
                'icon' => 'fas fa-hand-holding-usd',
                'label' => __('menu.customer_owed'),
                'role_type' => $this->RoleType('customer_owed.index'),
                'sub_menu' => null
            ],
            [
                // customer map management
                'route' => 'customer_map.index',
                'icon' => 'fas fa-map-marked-alt',
                'label' => __('menu.customer_map'),
                'role_type' => $this->RoleType('customer_map.index'),
                'sub_menu' => null
            ],
            [
                // staff checkin management
                'route' => 'staff.checkin',
                'icon' => 'fas fa-map',
                'label' => __('menu.staff_check_in'),
                'role_type' => $this->RoleType('customer_map.index'),
                'sub_menu' => null
            ],
            [
                // Sale management
                'route' => 'sale.index',
                'icon' => 'far fa-newspaper',
                'label' => __('menu.sale'),
                'role_type' => $this->RoleType('sale.index'),
                'sub_menu' => null
            ],
            [
                // Setting management
                'route' => 'report.index',
                'icon' => 'fas fa-cogs',
                'label' => __('menu.report'),
                'role_type' => $this->RoleType('report.index'),
                'sub_menu' => null
            ],
        ];
    }
    /*
     * render menu html
     */
    public function render()
    {
        return view('backends.partials.sidebar', [
            'menus' => $this->getMenus()
        ]);
    }

}

