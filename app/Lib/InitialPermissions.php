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



        ]);


        $role = Role::findOrCreate('read-only');

        $role->givePermissionTo([

        ]);


    }
}

