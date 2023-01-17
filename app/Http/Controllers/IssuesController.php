<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


class IssuesController extends Controller
{
    //

    public function allIssues (){

        return Inertia::render('Issues/All');

    }

}
