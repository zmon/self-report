<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;

use App\Organization;
use App\PreexistingCondition;
use App\RaceEthnicity;
use App\Symptom;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use App\SelfReport;
use App\Http\Requests\SelfReportApiRequest;


class SelfReportController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(SelfReportApiRequest $request, $org)
    {

        $self_report = new SelfReport;

        $incomming = $request->all();

        info(print_r($incomming, true));

       $r = $request->all();
        info(print_r($r,true));

        $data = $this->setFieldNames($incomming);
        $data2 = $this->setFieldNames($incomming['SendFields']);

        $data = array_merge($data, $data2);

        $organization_id = Organization::where('url_code', $org)->first()->id;

        $data['organization_id'] = $organization_id;

        // Lets have one county field
        $kscounty = data_get($data,'kscounty');
        $mocounty = data_get($data,'mocounty');

        info("|$kscounty|$mocounty|");

        $data['county_calc'] = !empty($kscounty) ? $kscounty : (!empty($mocounty) ? $mocounty : '--');

        // We will want to know when the form was submitted, which may not be the created_at time.
        $data['form_received_at'] = date('Y-m-d H:i:s');

        try {
            $self_report->add($data);
        } catch (Exception $e) {
            info($e->getMessage());
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        $this->addSymptoms($self_report, $incomming['SendFields']['_symptoms']);
        $this->addPreexistingCondition($self_report, $incomming['SendFields']['_preexisting_conditions']);
        $this->addRaceEthnicity($self_report, $incomming['SendFields']['_race_ethnicity']);

        return response()->json([
            'message' => 'Added record'
        ], 200);

    }

    private function addSymptoms($self_report, $records)
    {
        if($records)
        foreach ($records AS $i => $value) {
            $symptom = Symptom::firstOrCreate(['name' => $value]);
            $self_report->symptoms()->attach($symptom->id);
        }
    }

    private function addPreexistingCondition($self_report, $records)
    {
        if($records)
        foreach ($records AS $i => $value) {
            $symptom = PreexistingCondition::firstOrCreate(['name' => $value]);
            $self_report->preexisting_conditions()->attach($symptom->id);
        }
    }

    private function addRaceEthnicity($self_report, $records)
    {
        if($records)
        foreach ($records AS $i => $value) {
            $symptom = RaceEthnicity::firstOrCreate(['name' => $value]);
            $self_report->race_ethnicities()->attach($symptom->id);
        }
    }

    public function setFieldNames($a)
    {

        info(__METHOD__);
        $data = [];
        if($a)
        foreach ($a AS $i => $v) {
            if (!is_array($v)) {
                if ($f = $this->lookupFieldName($i)) {
                    $data[$f] = $v;
                }
            }
        }
        return $data;
    }

    public function lookupFieldName($name)
    {
        $lookup = [
            "ResponseReferenceId" => "name",
            "_exposed" => "exposed",
            "_public_private_exposure" => "public_private_exposure",
            "_state" => "state",
            "_kscounty" => "kscounty",
            "_mocounty" => "mocounty",
            "_city_kcmo" => "city_kcmo",
            "_zipcode" => "zipcode",
            "_selfreport_or_other" => "selfreport_or_other",
            "_whose_symptoms" => "whose_symptoms",
            "_sex" => "sex",
            "_age" => "age",
            "_any_other_symptoms" => "any_other_symptoms",
            "_symptom_severity" => "symptom_severity",
            "_immunocompromised" => "immunocompromised",
            "_symptom_start_date" => "symptom_start_date",
            "_followup_contact" => "followup_contact",
            "_preferred_contact_method" => "preferred_contact_method",
            "_is_voicemail_ok" => "is_voicemail_ok",
            "_crowded_setting" => "crowded_setting",
            "_anything_else" => "anything_else",
            "FormVersionId" => "FormVersionId",
            "FormId" => "FormId",
            "FormVersionNumber" => "FormVersionNumber",

            "ExternalId" => "ExternalId",
            "ExternalStatus" => "ExternalStatus",
            "ExternalRespondentId" => "ExternalRespondentId",
            "SourceType" => "SourceType",
            "SourceElementId" => "SourceElementId",
            "DataConnectionId" => "DataConnectionId",
            "CallCounter" => "CallCounter",
        ];


        if (array_key_exists($name, $lookup)) {
            return $lookup[$name];
        }

        return 'n';
    }


}
