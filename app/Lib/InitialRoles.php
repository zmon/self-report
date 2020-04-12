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


class InitialRoles
{

    function __construct()
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->setRole('paulb@savagesoft.com', 'super-admin');

    }

    private function setRole($email,$role) {
        print "Setting $email to $role\n";
        $user = User::where('email', $email)->first();
        $user->assignRole($role);
    }

}
