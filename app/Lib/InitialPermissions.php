<?php
/**
 * Created by PhpStorm.
 * User: paulb
 * Date: 2019-05-31
 * Time: 23:49
 */

namespace App\Lib;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;


class InitialPermissions
{
    function __construct()
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        Role::findOrCreate('super-admin');

        Permission::findOrCreate('invite index');
        Permission::findOrCreate('invite view');
        Permission::findOrCreate('invite export-pdf');
        Permission::findOrCreate('invite export-excel');
        Permission::findOrCreate('invite add');
        Permission::findOrCreate('invite edit');
        Permission::findOrCreate('invite delete');

        Permission::findOrCreate('organization index');
        Permission::findOrCreate('organization view');
        Permission::findOrCreate('organization export-pdf');
        Permission::findOrCreate('organization export-excel');
        Permission::findOrCreate('organization add');
        Permission::findOrCreate('organization edit');
        Permission::findOrCreate('organization delete');

        Permission::findOrCreate('self_report index');
        Permission::findOrCreate('self_report view');
        Permission::findOrCreate('self_report export-pdf');
        Permission::findOrCreate('self_report export-excel');
        Permission::findOrCreate('self_report add');
        Permission::findOrCreate('self_report edit');
        Permission::findOrCreate('self_report delete');


        Permission::findOrCreate('user index');
        Permission::findOrCreate('user add');
        Permission::findOrCreate('user edit');
        Permission::findOrCreate('user view');
        Permission::findOrCreate('user delete');
        Permission::findOrCreate('user export-pdf');
        Permission::findOrCreate('user export-excel');

        Permission::findOrCreate('user_role index');
        Permission::findOrCreate('user_role view');
        Permission::findOrCreate('user_role export-pdf');
        Permission::findOrCreate('user_role export-excel');
        Permission::findOrCreate('user_role add');
        Permission::findOrCreate('user_role edit');
        Permission::findOrCreate('user_role delete');


        Permission::findOrCreate('always fail');

//        Permission::findOrCreate('client');
//        Permission::findOrCreate('contractor');


        $role = Role::findOrCreate('cant');

        $role->givePermissionTo(['always fail']);


        $role = Role::findOrCreate('only index');
        // For Testing
//        $role->givePermissionTo(['department index']);
//        $role->givePermissionTo(['department index']);
//        $role->givePermissionTo(['service_type index']);


        $role = Role::findOrCreate('Client Admin');
//        $role->update(['can_assign' => true]);
        $role->givePermissionTo([

            'invite index',
            'invite add',
            'invite edit',
            'invite view',
            'invite delete',
            'invite export-pdf',
            'invite export-excel',

            'organization index',
            'organization view',
            'organization export-pdf',
            'organization export-excel',
            'organization add',
            'organization edit',
            'organization delete',

            'self_report index',
            'self_report view',
            'self_report export-pdf',
            'self_report export-excel',
            'self_report add',
            'self_report edit',
            'self_report delete',



            'user index',
            'user add',
            'user edit',
            'user view',
            'user delete',
            'user export-pdf',
            'user export-excel',

            'user_role index',
            'user_role add',
            'user_role edit',
            'user_role view',
            'user_role delete',
            'user_role export-pdf',
            'user_role export-excel',


        ]);


        $role = Role::findOrCreate('read-only');

        $role->givePermissionTo([
            'organization index',
            'organization view',

            'self_report index',
            'self_report view',
        ]);


    }
}

