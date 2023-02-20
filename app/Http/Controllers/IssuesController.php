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
    /*
    list all issues
    If super admin list all open issues
    If staff list issues depending on assigned permissions
    If normal user list issues created by the user
     */
    public function allIssues (){
      $user=Auth::user();

      //if user is staff
      if ($user->is_staff) {
       $openIssues=$this->getStaffIssues($user);
      }
      else{
        $openIssues=Issues::with(['issueCategory','issuedBy:id,name','assignedTo:id,name'])
        ->where('issued_by',$user->id)
        ->orderBy('id','desc')
        ->get();
      }

        return Inertia::render('Issues',[
            'issue_categories' => IssuesCategory::all(),
            'issues' => $openIssues,
             'staff_permissions' => $staff_permissions ?? '',
        ]);
    }


    protected function getStaffIssues($user)
    {
        if ($user->hasRole('Super-Admin')) {
            //gets all the issues
            $openIssues=Issues::with(['issueCategory','issuedBy:id,name','assignedTo:id,name'])->orderBy('id','desc')->get();
            return $openIssues;
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
                $openIssues=$allIssues->whereIn('issueCategory.name',$staff_permissions);
                return $openIssues;
          }
    }


    public function newIssue(Request $request){

        //issues are to be submitted by other users but not staff
       if ($request->user()->is_staff ) {
          abort(403, 'Unauthorized action.');
          return false;
     }

       $this->validate($request,[
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
