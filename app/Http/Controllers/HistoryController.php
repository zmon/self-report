<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{


    public function department(Department $department)
    {
        $history = $department->load(['histories' => function ($q) {
            $q->with(['user']);
        }]);
        return $history;
        }
}
