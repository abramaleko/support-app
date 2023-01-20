<?php

namespace App\Http\Controllers;

use App\Models\Issues;
use App\Models\IssuesCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;


class IssuesController extends Controller
{

    //list all the issues
    public function allIssues (){


        return Inertia::render('Issues/All',[
            'issue_categories' => IssuesCategory::all(),
            'issues' => Issues::with(['issueCategory','issuedBy:id,name','assignedTo:id,name'])->orderBy('id','desc')->get()
        ]);
    }


    public function newIssue(Request $request){

        $validatedData=$this->validate($request,[
            'issue_title' => 'required|string',
            'issue_description' => 'required|string',
            'issue_category' => 'required|integer',
        ]);

        Issues::create([
            'issue_id' => mt_rand(1000,10000),
            'title' => $request->issue_title,
            'description' => $request->issue_description,
            'issue_category_id' => $request->issue_category,
            'issued_by' => $request->user()->id,
        ]);

        return to_route('issues.all');


    }

}
