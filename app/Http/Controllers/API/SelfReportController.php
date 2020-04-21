<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use App\SelfReport;
use App\Http\Requests\SelfReportApiRequest;


class SelfReportController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SelfReportApiRequest $request)
    {

        $self_report = new \App\SelfReport;

        $incomming = $request->all();

        info(print_r($incomming,true));

        $data = $this->setFieldNames($incomming);
        $data2 = $this->setFieldNames($incomming['SendFields']);

        $data = array_merge($data,$data2);



        try {
            $self_report->add($data);
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        \Session::flash('flash_success_message', 'Self Reports ' . $self_report->name . ' was added.');

        return response()->json([
            'message' => 'Added record'
        ], 200);

    }

    public function setFieldNames($a) {

        info(__METHOD__);
        $data = [];
        foreach ($a AS $i => $v) {
            if (!is_array($v)) {
                if ($f = $this->lookupFieldName($i)) {
                    $data[$f] = $v;
                }
            }
        }
        return $data;
    }

    public function lookupFieldName($name) {
        $lookup = [
            "ResponseReferenceId" => "name",
            "_exposed" => "exposed",
            "_public_private_exposure" => "public_private_exposure",
            "_state" => "state",
            "_kscounty" => "kscounty",
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


        if ( array_key_exists($name,$lookup)) {
            return $lookup[$name];
        }

        return 'n';
    }


}
