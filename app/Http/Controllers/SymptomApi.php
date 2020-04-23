<?php

namespace App\Http\Controllers;

use App\Symptom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SymptomIndexRequest;

class SymptomApi extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SymptomIndexRequest $request)
    {

        $page = $request->get('page', '1');                // Pagination looks at the request
        //    so not quite sure if we need this
        $column = $request->get('column', 'Name');
        $direction = $request->get('direction', '-1');
        $keyword = $request->get('keyword', '');

        // Save the search parameters so we can remember when we go back to the index
        //   The page is being done by Laravel
        session([
            'symptom_page' => $page,
            'symptom_column' => $column,
            'symptom_direction' => $direction,
            'symptom_keyword' => $keyword
        ]);

        $keyword = $keyword != 'null' ? $keyword : '';
        $column = $column ? mb_strtolower($column) : 'name';

        return Symptom::indexData(10, $column, $direction, $keyword);
    }

    /**
     * Returns "options" for HTML select
     * @return array
     */
    public function getOptions() {

        return Symptom::getOptions();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
