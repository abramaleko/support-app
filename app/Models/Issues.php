<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issues extends Model
{
    use HasFactory;

    protected $fillable=[
        'issue_id',
        'title',
        'description',
        'issue_category_id',
        'issued_by'
    ];

    public function issueCategory()
    {
        return $this->belongsTo(IssuesCategory::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class,'issued_by');
    }

    public function assignedTo(){

        return $this->belongsTo(User::class,'assigned_to');
    }

    /*
      Get the chats for the issue
     */
    public function chats(){
        return $this->hasMany(IssueHistory::class,'issue_id');
    }
}
