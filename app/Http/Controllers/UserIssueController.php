<?php

namespace App\Http\Controllers;

use App\Events\Chat;
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
        return $query->orderBy('id','desc')->first();
    }])
         ->where('issued_by',Auth::user()->id)
         ->orderBy('id','desc')
         ->get();

         return Inertia::render('UserIssues/AllMessages',[
             'issues' => $issues,
             'user_id' => Auth::user()->id,
         ]);
     }

     public function viewChat($issue_id){

        $issue=Issues::where('issue_id',$issue_id)
        ->with(['issuedBy:id,name','assignedTo:id,name'])->first();

           //if the user is not the creator of the issue
        if ($issue->issued_by != Auth::user()->id) {
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
        return Inertia::render('UserIssues/ViewMessage',[
            'issue' => $issue,
            'messages' =>$messages,
        ]);
     }

     public function sendMessage(Request $request){
        $this->validate($request,['text' => 'required|string']);
        $user=Auth::user()->id;

        $chat=new IssueHistory();
        $chat->issue_id=$request->issue_id;
        $chat->user_id=$user;
        $chat->text=$request->text;
        $chat->save();

        broadcast(new Chat($request->issue_id, $request->text,$user))->toOthers();

       return to_route('user.view-message',$request->issue_code);
     }
}
