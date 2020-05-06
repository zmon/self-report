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

        Permission::findOrCreate('preexisting_condition index');
        Permission::findOrCreate('preexisting_condition view');
        Permission::findOrCreate('preexisting_condition export-pdf');
        Permission::findOrCreate('preexisting_condition export-excel');
        Permission::findOrCreate('preexisting_condition add');
        Permission::findOrCreate('preexisting_condition edit');
        Permission::findOrCreate('preexisting_condition delete');

        Permission::findOrCreate('race_ethnicity index');
        Permission::findOrCreate('race_ethnicity view');
        Permission::findOrCreate('race_ethnicity export-pdf');
        Permission::findOrCreate('race_ethnicity export-excel');
        Permission::findOrCreate('race_ethnicity add');
        Permission::findOrCreate('race_ethnicity edit');
        Permission::findOrCreate('race_ethnicity delete');



        Permission::findOrCreate('self_report index');
        Permission::findOrCreate('self_report view');
        Permission::findOrCreate('self_report export-pdf');
        Permission::findOrCreate('self_report export-excel');
        Permission::findOrCreate('self_report add');
        Permission::findOrCreate('self_report edit');
        Permission::findOrCreate('self_report delete');

        Permission::findOrCreate('symptom index');
        Permission::findOrCreate('symptom view');
        Permission::findOrCreate('symptom export-pdf');
        Permission::findOrCreate('symptom export-excel');
        Permission::findOrCreate('symptom add');
        Permission::findOrCreate('symptom edit');
        Permission::findOrCreate('symptom delete');

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


        Role::findOrCreate('super-admin');


        $role = Role::findOrCreate('Admin');
        $role->update(['can_assign' => true]);
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

            'preexisting_condition index',
            'preexisting_condition view',
            'preexisting_condition export-pdf',
            'preexisting_condition export-excel',

            'race_ethnicity index',
            'race_ethnicity view',
            'race_ethnicity export-pdf',
            'race_ethnicity export-excel',

            'self_report index',
            'self_report view',
            'self_report export-pdf',

            'symptom index',
            'symptom view',
            'symptom export-pdf',
            'symptom export-excel',

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

        $role = Role::findOrCreate('Health Athority');

        $role->givePermissionTo([

            'self_report index',
            'self_report view',
            'self_report export-pdf',
            'self_report export-excel',

        ]);


    }
}

