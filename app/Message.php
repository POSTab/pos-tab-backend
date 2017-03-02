<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	
	protected $table="msgcenter";
	
	protected $fillable=['sent_to', 'project_id', 'sent_by', 'msg_title', 'message', 'deleted_flag', 'read_flag', 'sent_flag', 'reply', 'created_at', 'thread','updated_at'];
	
	protected $hidden = ['deleted_flag', 'spair_flag','updated_at'];
	
    public function senders(){

          return $this->hasOne('App\User','id','sent_by');
    }
	
	public function receivers(){
		
		return $this->hasOne('App\User','id','sent_to');
		
	}
	public function projects(){
		return $this->hasOne('App\Project','id','project_id');
	}
    
}
