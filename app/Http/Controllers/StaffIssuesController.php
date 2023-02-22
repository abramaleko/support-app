<?php

namespace App\Http\Controllers;

use App\Models\IssueHistory;
use App\Models\Issues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class StaffIssuesController extends Controller
{
    /*
        This controller will be managing all the functions for
        staff responding to issues
     */


     public function staffChats()
     {
        //get all the assigned issues in latest order
        $issues=Issues::with(['chats'=>function($query){
            return $query->orderBy('id','desc')->first();
           }])
            ->where('assigned_to',Auth::user()->id)
            ->orderBy('id','desc')
            ->get();

        return Inertia::render('StaffIssues/AllMessages',[
            'issues' => $issues,
            'user_id' => Auth::user()->id,
        ]);
     }


     //view the chats
     public function viewChat($issue_id){

        $issue=Issues::where('issue_id',$issue_id)
        ->with(['issuedBy:id,name','assignedTo:id,name'])->first();

               //if the staff is not assigned to issue abort
               if ($issue->assigned_to != Auth::user()->id) {
                abort(403, 'Unauthorized action.');
            }

        //get messages
        $messages=IssueHistory::where('issue_id',$issue->id)->get();

        //check who sent the last message if not the the admin update it to read
        $message=$messages->last();
        if ($message && $message->user_id != Auth::user()->id) {
            $message->read=true;
            $message->save();
        }

        return Inertia::render('StaffIssues/ViewMessage',[
            'issue' => $issue,
            'messages' =>$messages,
        ]);
     }

     public function getAssignedIssue(Request $request)
     {
        //finds the issue and assigns it to staff
        $issue=Issues::find($request->issue_id);
        $issue->assigned_to=$request->user()->id;
        $issue->status=2;
        $issue->save();

        return to_route('staff.view-message',$issue->issue_id);
     }

     public function sendMessage(Request $request){
        $this->validate($request,['text' => 'required|string']);

        $chat=new IssueHistory();
        $chat->issue_id=$request->issue_id;
        $chat->user_id=Auth::user()->id;
        $chat->text=$request->text;
        $chat->save();

        return to_route('staff.view-message',$request->issue_code);
     }
}
