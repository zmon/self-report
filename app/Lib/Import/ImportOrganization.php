<?php

namespace App\Lib\Import;

use App\Lib\Import\GetDbColumns;
use App\Organization;
use DB;

class ImportOrganization
{
    public $fields = [

            'id' => ['name' => 'id'],
            'name' => ['name' => 'name'],
            'alias' => ['name' => 'alias'],
            'url_code' => ['name' => 'url_code'],
            'contact_name' => ['name' => 'contact_name'],
            'title' => ['name' => 'title'],
            'phone_1' => ['name' => 'phone_1'],
            'email' => ['name' => 'email'],
            'notes' => ['name' => 'notes'],
            'active' => ['name' => 'active'],

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

            $Org = new Organization();
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
