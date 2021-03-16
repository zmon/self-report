<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreexistingConditionIndexRequest;
use App\PreexistingCondition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PreexistingConditionApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PreexistingConditionIndexRequest $request)
    {
        $page = $request->get('page', '1');                // Pagination looks at the request
        //    so not quite sure if we need this
        $column = $request->get('column', 'Name');
        $direction = $request->get('direction', '-1');
        $keyword = $request->get('keyword', '');

        // Save the search parameters so we can remember when we go back to the index
        //   The page is being done by Laravel
        session([
            'preexisting_condition_page' => $page,
            'preexisting_condition_column' => $column,
            'preexisting_condition_direction' => $direction,
            'preexisting_condition_keyword' => $keyword,
        ]);

        $keyword = $keyword != 'null' ? $keyword : '';
        $column = $column ? mb_strtolower($column) : 'name';

        return PreexistingCondition::indexData(10, $column, $direction, $keyword);
    }

    /**
     * Returns "options" for HTML select
     * @return array
     */
    public function getOptions()
    {
        return PreexistingCondition::getOptions();
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
