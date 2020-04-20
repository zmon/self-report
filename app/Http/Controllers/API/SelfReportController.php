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
    public function store(Request $request)
    {


	info(print_r($request->all(),true));
        return response()->json([
            'message' => 'Added record'
        ], 200);
        $self_report = new \App\SelfReport;

        try {
            $self_report->add($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        \Session::flash('flash_success_message', 'Self Reports ' . $self_report->name . ' was added.');

        return response()->json([
            'message' => 'Added record'
        ], 200);

    }


}
