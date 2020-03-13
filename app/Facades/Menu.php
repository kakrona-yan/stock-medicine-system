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
            [
                // category management
                'route' => 'category.index',
                'icon' => 'fas fa-tags',
                'label' => __('menu.category'),
                'role_type' => $this->RoleType('category.index'),
                'sub_menu' => null
            ],
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
                'route' => 'customer_type.index',
                'icon' => 'fas fa-user',
                'label' => __('menu.customer_type'),
                'role_type' => $this->RoleType('customer_type.index'),
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
                // staff management
                'route' => 'staff.index',
                'icon' => 'fas fa-user',
                'label' => __('menu.staff'),
                'role_type' => $this->RoleType('staff.index'),
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
            // [
            //     // Setting management
            //     'route' => 'setting.index',
            //     'icon' => 'fas fa-cogs',
            //     'label' => __('menu.setting'),
            //     'role_type' => $this->RoleType('setting.index'),
            //     'sub_menu' => null
            // ],
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

