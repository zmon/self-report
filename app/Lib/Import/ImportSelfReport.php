<?php

namespace App\Lib\Import;

use App\Lib\Import\GetDbColumns;
use App\SelfReport;
use DB;

class ImportSelfReport
{
    public $fields = [

            'id' => ['name' => 'id'],
            'organization_id' => ['name' => 'organization_id'],
            'name' => ['name' => 'name'],
            'exposed' => ['name' => 'exposed'],
            'public_private_exposure' => ['name' => 'public_private_exposure'],
            'state' => ['name' => 'state'],
            'kscounty' => ['name' => 'kscounty'],
            'mocounty' => ['name' => 'mocounty'],
            'city_kcmo' => ['name' => 'city_kcmo'],
            'zipcode' => ['name' => 'zipcode'],
            'selfreport_or_other' => ['name' => 'selfreport_or_other'],
            'whose_symptoms' => ['name' => 'whose_symptoms'],
            'sex' => ['name' => 'sex'],
            'age' => ['name' => 'age'],
            'any_other_symptoms' => ['name' => 'any_other_symptoms'],
            'symptom_severity' => ['name' => 'symptom_severity'],
            'immunocompromised' => ['name' => 'immunocompromised'],
            'symptom_start_date' => ['name' => 'symptom_start_date'],
            'followup_contact' => ['name' => 'followup_contact'],
            'preferred_contact_method' => ['name' => 'preferred_contact_method'],
            'is_voicemail_ok' => ['name' => 'is_voicemail_ok'],
            'crowded_setting' => ['name' => 'crowded_setting'],
            'anything_else' => ['name' => 'anything_else'],
            'FormVersionId' => ['name' => 'FormVersionId'],
            'FormId' => ['name' => 'FormId'],
            'FormVersionNumber' => ['name' => 'FormVersionNumber'],
            'ExternalId' => ['name' => 'ExternalId'],
            'ExternalStatus' => ['name' => 'ExternalStatus'],
            'ExternalRespondentId' => ['name' => 'ExternalRespondentId'],
            'SourceType' => ['name' => 'SourceType'],
            'SourceElementId' => ['name' => 'SourceElementId'],
            'DataConnectionId' => ['name' => 'DataConnectionId'],
            'CallCounter' => ['name' => 'CallCounter'],
            'county_calc' => ['name' => 'county_calc'],
            'form_received_at' => ['name' => 'form_received_at'],

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

            $Org = new SelfReport();
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
