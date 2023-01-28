<?php

namespace App\Http\Controllers;

use App\Models\IssuesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


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
            'issue_categories' => IssuesCategory::all()
        ]);
    }

    public function newCategory(Request $request)
    {
      $request->validate([
        'issue_category' => 'required|string'
      ]);
        IssuesCategory::create([
            'name' => $request->issue_category
        ]);

        return to_route('settings');
    }
}
