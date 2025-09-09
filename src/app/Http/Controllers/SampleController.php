<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index(): Factory|View
    {
        // We will add logic here later to fetch data from the database.
        // For now, it just shows the view.
        return view('upload');
    }
}
