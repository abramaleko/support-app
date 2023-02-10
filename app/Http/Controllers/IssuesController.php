<?php

namespace App\Http\Controllers;

use App\Models\Issues;
use App\Models\IssuesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class IssuesController extends Controller
{

    //list all the issues
    public function allIssues (){
      $user=Auth::user();
      if ($user->hasRole('Super-Admin')) {
        //gets all the issues
        $assingnedIssues=Issues::with(['issueCategory','issuedBy:id,name','assignedTo:id,name'])->orderBy('id','desc')->get();
      }
      else {
        //get staff permissions
            $permissions=Auth::user()->getAllPermissions();
            $staff_permissions=[];
            foreach ($permissions as $permission) {
            $name=str_replace('Manage ','',$permission->name);
            array_push($staff_permissions,$name);
            }

            $allIssues=Issues::with(['issueCategory','issuedBy:id,name','assignedTo:id,name'])->orderBy('id','desc')->get();

            //get the issues which the user has permissions to manage
            $assingnedIssues=$allIssues->whereIn('issueCategory.name',$staff_permissions);
      }

        return Inertia::render('Issues/All',[
            'issue_categories' => IssuesCategory::all(),
            'issues' => $assingnedIssues,
             'staff_permissions' => $staff_permissions ?? '',
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
