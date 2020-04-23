<?php


namespace App\Lib\Import;
use App\Lib\Import\GetDbColumns;

use App\User;
use DB;

class ImportUser{


    var $fields = [

            "id" => ["name" => "id"],
            "organization_id" => ["name" => "organization_id"],
            "name" => ["name" => "name"],
            "email" => ["name" => "email"],
            "active" => ["name" => "active"],
            "email_verified_at" => ["name" => "email_verified_at"],
            "password" => ["name" => "password"],
            "remember_token" => ["name" => "remember_token"],
    
//        "created_at" => ["name" => "created_at"],
//        "created_by" => ["name" => "created_by"],
//        "updated_at" => ["name" => "updated_at"],
//        "modified_by" => ["name" => "modified_by"],
//        "purged_by" => ["name" => "purged_by"],
    ];

    function import($database, $tablename)
    {

        print "Importing $tablename\n";

        $records = DB::connection($database)->select("select * from " . $tablename);

        $count = 0;
        foreach ($records AS $record) {
            //if ($count++ > 5) die;

            $new_rec = $this->clean($record);

            $Org = new User();
            $Org->add($new_rec);

        }

    }

    function clean($record) {
        $data = [];
        foreach ($this->fields AS $org_name => $field) {
            $data[$field['name']] = $record->$org_name;
        }

        return $data;
    }

}

