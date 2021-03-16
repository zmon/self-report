<?php

namespace App\Lib\Import;

use App\Lib\Import\GetDbColumns;
use App\User;
use DB;

class ImportUser
{
    public $fields = [

            'id' => ['name' => 'id'],
            'organization_id' => ['name' => 'organization_id'],
            'name' => ['name' => 'name'],
            'email' => ['name' => 'email'],
            'active' => ['name' => 'active'],
            'email_verified_at' => ['name' => 'email_verified_at'],
            'password' => ['name' => 'password'],
            'remember_token' => ['name' => 'remember_token'],

//        "created_at" => ["name" => "created_at"],
//        "created_by" => ["name" => "created_by"],
//        "updated_at" => ["name" => "updated_at"],
//        "modified_by" => ["name" => "modified_by"],
//        "purged_by" => ["name" => "purged_by"],
    ];

    public function import($database, $tablename)
    {
        echo "Importing $tablename\n";

        $records = DB::connection($database)->select('select * from '.$tablename);

        $count = 0;
        foreach ($records as $record) {
            //if ($count++ > 5) die;

            $new_rec = $this->clean($record);

            $Org = new User();
            $Org->add($new_rec);
        }
    }

    public function clean($record)
    {
        $data = [];
        foreach ($this->fields as $org_name => $field) {
            $data[$field['name']] = $record->$org_name;
        }

        return $data;
    }
}
