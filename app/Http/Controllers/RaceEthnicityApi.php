<?php

namespace App\Http\Controllers;

use App\RaceEthnicity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RaceEthnicityIndexRequest;
use Illuminate\Http\Response;

class RaceEthnicityApi extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(RaceEthnicityIndexRequest $request)
    {

        $page = $request->get('page', '1');                // Pagination looks at the request
        //    so not quite sure if we need this
        $column = $request->get('column', 'Name');
        $direction = $request->get('direction', '-1');
        $keyword = $request->get('keyword', '');

        // Save the search parameters so we can remember when we go back to the index
        //   The page is being done by Laravel
        session([
            'race_ethnicity_page' => $page,
            'race_ethnicity_column' => $column,
            'race_ethnicity_direction' => $direction,
            'race_ethnicity_keyword' => $keyword
        ]);

        $keyword = $keyword != 'null' ? $keyword : '';
        $column = $column ? mb_strtolower($column) : 'name';

        return RaceEthnicity::indexData(10, $column, $direction, $keyword);
    }

    /**
     * Returns "options" for HTML select
     * @return array
     */
    public function getOptions()
    {

        return RaceEthnicity::getOptions();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
