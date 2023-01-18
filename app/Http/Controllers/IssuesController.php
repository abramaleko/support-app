<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


class IssuesController extends Controller
{

    //list all the issues
    public function allIssues (){

        return Inertia::render('Issues/All');
    }

}
