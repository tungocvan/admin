<?php

return [
    [
        'label' => 'Dashboard',
        'icon' => 'fas fa-home',
        'route' => 'admin.dashboard',
        'permission' => 'view dashboard',
    ],
    [
        'label' => 'Người dùng',
        'icon' => 'fas fa-users',
        'permission' => 'manage users',
        'children' => [
            [
                'label' => 'Danh sách',
                'icon' => 'fas fa-list',
                'route' => 'users.index',
                'permission' => 'users-list',
            ],
            [
                'label' => 'Phân Quyền',
                'icon' => 'fas fa-plus',
                'route' => 'role.index',
                'permission' => 'role-list',
            ],
        ],
    ],
    [
        'label' => 'Template HTML',
        'icon' => 'fas fa-home',
        'permission' => 'template-list',
        'children' => [
            [
                'label' => 'Dashboard',
                'icon' => 'fas fa-list',
                'route' => 'template.index',
                'permission' => 'template-list',
            ],
            [
                'label' => 'Form Add',
                'icon' => 'fas fa-plus',
                'route' => 'template.form-add',
                'permission' => 'template-list',
            ],
            [
                'label' => 'Form Basic',
                'icon' => 'fas fa-plus',
                'route' => 'template.form-basic',
                'permission' => 'template-list',
            ],
            [
                'label' => 'Form Select',
                'icon' => 'fas fa-plus',
                'route' => 'template.form-select',
                'permission' => 'template-list',
            ],
            [
                'label' => 'Components',
                'icon' => 'fas fa-plus',
                'route' => 'template.components',
                'permission' => 'template-list',
            ],
        ],
    ],
];
