<?php

namespace App\Http\Controllers;

use App\Models\IssueHistory;
use App\Models\Issues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserIssueController extends Controller
{
     //create a middleware
     public function __construct()
     {
        $this->middleware('isUser');
     }

     public function getMessages()
     {
       //get all the assigned issues in latest order
       $issues=Issues::with(['chats'=>function($query){
         return $query->first();
        }])
         ->where('issued_by',Auth::user()->id)
         ->orderBy('id','desc')
         ->get();

         return Inertia::render('UserIssues/AllMessages',[
             'issues' => $issues,
         ]);
     }

     public function viewChat($issue_id){

        $issue=Issues::where('issue_id',$issue_id)
        ->with(['issuedBy:id,name','assignedTo:id,name'])->first();

        //get messages
        $messages=IssueHistory::where('issue_id',$issue->id)->get();

        //if the staff is not assigned to issue abort
        if ($issue->issued_by != Auth::user()->id) {
        abort(403, 'Unauthorized action.');
    }

        return Inertia::render('UserIssues/ViewMessage',[
            'issue' => $issue,
            'messages' =>$messages,
        ]);
     }

     public function sendMessage(Request $request){
        $this->validate($request,['text' => 'required|string']);

        $chat=new IssueHistory();
        $chat->issue_id=$request->issue_id;
        $chat->user_id=Auth::user()->id;
        $chat->text=$request->text;
        $chat->save();

        return to_route('user.view-message',$request->issue_code);

     }
}
