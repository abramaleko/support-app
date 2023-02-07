<?php

namespace App\Http\Controllers;

use App\Models\IssuesCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class SettingsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('checkUserIsAdmin');
    }

    public function showSettings()
    {
        /*
        Get the staffs
        loop throught the staffs and get their permissions
        Create a string version of the permission
        If any staff that has no permissions then thats a super admin
         */
        $staffs=User::with('permissions:id,name')->where('is_staff',true)->get();
        foreach ($staffs as $staff) {

            if ($staff->permissions) {
                $assignedPermissions=[];

                foreach ($staff->permissions as $permission) {
                   array_push($assignedPermissions,$permission->name);
                }
                $staff->stringifiedPermissions=implode(", ",$assignedPermissions);
            }
        }


       return Inertia::render('Settings/Admin/Settings',[
            'issue_categories' => IssuesCategory::all(),
            'permissions' => Permission::all(),
            'staffs' => $staffs,
        ]);
    }

    public function newCategory(Request $request)
    {
        //validate the category
      $request->validate([
        'issue_category' => 'required|string'
      ]);

      //create a new issue category
        IssuesCategory::create([
            'name' => $request->issue_category
        ]);

        //create a permission for that category
        Permission::create([
            'name' => 'Manage '.$request->issue_category,
        ]);

        return to_route('settings');
    }

    public function newStaff(Request $request)
    {
        //validates the input
        $request->validate([
            'staff_name' => 'required|string',
            'staff_email' => 'required|email|unique:users,email',
            'staff_permissions' => 'required',
        ]);

        //creates the user with the default password from issuesHub config file
        $user=User::create([
            'name' => $request->staff_name,
            'email' => $request->staff_email,
            'password' => Hash::make(config('issuesHub.staff_password')),
            'is_staff' => true,
        ]);

        //assigns the permissions
        foreach ($request->staff_permissions as $permission) {
            $user->givePermissionTo($permission);
        }

        return to_route('settings');
    }

    public function updatePermissions(Request $request)
    {
        $request->validate([
            'current_permissions' => 'required'
        ]);

        $staff=User::find($request->user_id);

        $staff->syncPermissions($request->current_permissions);

        return to_route('settings');

    }
}
