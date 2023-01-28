<?php

namespace App\Http\Controllers;

use App\Models\IssuesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return Inertia::render('Settings/Admin/Settings',[
            'issue_categories' => IssuesCategory::all(),
            'permissions' => Permission::all()
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
}
