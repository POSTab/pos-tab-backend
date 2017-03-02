<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Message;
use DB;
class MessageController extends Controller
{
  
   public function _construct(){
	   
	   date_default_timezone_set("Asia/Kolkata");
   }
  
  public function msgInbox($id){
	  
	  $message = Message::with(['senders','projects'])->groupby('thread')->distinct()->where(['sent_to'=>$id,'sent_flag'=>'1'])->where('deleted_flag','!=','2')->where('deleted_flag','!=','3')->select('id','sent_to', 'project_id', 'sent_by', 'msg_title', 'message', 'deleted_flag', 'read_flag', 'sent_flag', 'reply', 'created_at', 'thread')->orderby('id','asc')->get(['thread']);
	  
	  return json_encode($message);
	  
	  
  }
  
  public function msgSent($id){
	  
	  $message = Message::with(['receivers','projects'])->where(['sent_by'=>$id,'sent_flag'=>'1',])->where('deleted_flag','!=','1')->where('deleted_flag','!=','3')->orderby('created_at','desc')->get();
	  return json_encode($message);
	  
	  
  }
 
  public function msgDraft($id){
	  
	  $message = Message::with(['projects'])->where(['sent_by'=>$id,'sent_flag'=>'0'])->get();
	  return json_encode($message);
	  
	  
  }
  
  public function msgRead($id,$userid){
	      $updateread_flag =  Message::where(['thread'=>$id,'sent_to'=>$userid])->update(['read_flag'=>'1']);
	      
	 	  $message = Message::with(['senders','projects'])->where(['thread'=>$id,'sent_flag'=>'1'])->get();
		  
	 	  
	  
	  return json_encode($message);
	  
	  
  }
  
  public function sendMsg(Request $request){
	
	  $thread = Message::max('thread');
	  $receiverIds = explode(',',$request->Input('receiversIds'));
	  $msgTitle=$request->Input('msg_title');
	  $message=$request->Input('message');
	  $project_id=$request->Input('project_id');
	  $sent_by=$request->Input('senderId');
	  for($i=0;$i<count($receiverIds);$i++){
		  $sent_to=$receiverIds[$i];
		  $send=Message::create([
	      'sent_to'=>$sent_to,
		  'project_id'=>$project_id,
		  'sent_by'=>$sent_by,
		  'msg_title'=>$msgTitle,
		  'message'=>$message,
		  'deleted_flag'=>0,
		  'read_flag'=>0,
		  'sent_flag'=>1, 
		  'reply'=>0, 
		  'created_at'=>date('Y-m-d H:m:s'), 
		  'thread'=> ++$thread,
	  ]);
		  
	  }

	  if($send){    
		                    //Send Push Notification
				for($i=0;$i<count($receiverIds);$i++){
							$getpushid=DB::table('push_id')
									->where('user_id',$receiverIds[$i])
									->get();
                                                        
                                                               // print_r($getpushid);
							if($getpushid)
							{
								$device1=$getpushid[0]->device1;
								$device2=$getpushid[0]->device2;
								if($device1==$device2)
								{
									$device2="";
								}
								$registatoin_ids = array($device1,$device2);
								$message = array("title" => "School App","message"=>$msgTitle);
								$result= PushController::send_notification($registatoin_ids, $message);
							}
					}
		  return 1;
	  }
		  
	 
	  
  }
  
  
    public function saveMsg(Request $request){
	  $thread = Message::max('thread');
	  $msgTitle=$request->Input('msg_title');
	  $message=$request->Input('message');
	  $project_id=$request->Input('project_id');
	  $sent_by=$request->Input('senderId');
	 
		  $send=Message::create([
	      'sent_to'=>0,
		  'project_id'=>$project_id,
		  'sent_by'=>$sent_by,
		  'msg_title'=>$msgTitle,
		  'message'=>$message,
		  'deleted_flag'=>0,
		  'read_flag'=>0,
		  'sent_flag'=>0, 
		  'reply'=>0, 
		  'created_at'=>date('Y-m-d H:m:s'), 
		  'thread'=> 0,
	  ]);
		  
	

	  if($send){
		  return 1;
	  }
	//  print_r($receiverIds); 
	  
	 
	  
  }
  
  public function replyMsg(Request $request){
	  $thread = $request->Input('threadid');
	  $reply = $request->Input('msgId');
	  $msgTitle=$request->Input('msgTitle');
	  $message=$request->Input('message');
	  $project_id=$request->Input('project_id');
	  $sent_by=$request->Input('sender');
	  $sent_to=$request->Input('receiver');
	   
	
       	 
	 
		  $send=Message::create([
	      'sent_to'=>$sent_to,
		  'project_id'=>$project_id,
		  'sent_by'=>$sent_by,
		  'msg_title'=>$msgTitle,
		  'message'=>$message,
		  'deleted_flag'=>0,
		  'read_flag'=>0,
		  'sent_flag'=>1, 
		  'reply'=>$reply, 
		  'created_at'=>date('Y-m-d H:m:s'), 
		  'thread'=> $thread
	  ]);
		  
	

	  if($send){
		  
		  $getpushid=DB::table('push_id')
									->where('user_id',$sent_to)
									->get();
                                                        
                                                               // print_r($getpushid);
							if($getpushid)
							{
								$device1=$getpushid[0]->device1;
								$device2=$getpushid[0]->device2;
								if($device1==$device2)
								{
									$device2="";
								}
								$registatoin_ids = array($device1,$device2);
								$message = array("title" => "School App","message"=>$msgTitle);
								$result= PushController::send_notification($registatoin_ids, $message);
							}
		  
		  return 1;
	  }
	//  print_r($receiverIds); 
	 
	}
	
	  public function deleteSent($id){
	      $checkFlag = Message::where('id',$id)->select('deleted_flag')->first();//(['deleted_flag'=>'1']);
		 //  echo $checkFlag->deleted_flag;
		   if( $checkFlag->deleted_flag == 0){
                  $delete = Message::where('id',$id)->update(['deleted_flag'=>'1']);
		   }else{
			   $delete = Message::where('id',$id)->update(['deleted_flag'=>'3']);
		   }
		// 
		
		 // return $delete;
	  }
	  
     public function deleteDraft($id){
		 $delete = Message::where('id',$id)->delete();
		
		  return $delete;
		 
	 }
	 
  public function deleteFrminbox($id,$sent_to){
	
		 $msg = Message::where('thread',$id)->where('sent_to',$sent_to)->get();
		  for($i=0;$i<count($msg);$i++){
			  if( $msg[$i]->deleted_flag == 0){
				//  echo 'flagcheck 0 '.$msg[$i]->deleted_flag."id".$msg[$i]->id;
                 $delete = Message::where('thread',$id)->where('sent_to',$sent_to)->update(['deleted_flag'=>'2']);
		   }else{
			  //  echo  'flagcheck 1 '.$msg[$i]->deleted_flag;
		    	 $delete = Message::where('thread',$id)->where('sent_to',$sent_to)->update(['deleted_flag'=>'3']);
		   }
		  }
		  return $delete;
	  }
	 



		public function getmsgCount($id){
			
			$sent =   Message::where(['sent_by'=>$id,'sent_flag'=>'1',])->where('deleted_flag','!=','1')->where('deleted_flag','!=','3')->get();
			$draft =   Message::where(['sent_by'=>$id,'sent_flag'=>'0',])->where('deleted_flag','!=','1')->where('deleted_flag','!=','3')->get();
			$inbox =   Message::where(['sent_to'=>$id,'sent_flag'=>'1',])->where('deleted_flag','!=','2')->where('deleted_flag','!=','3')->get();
			$unread =   Message::where(['sent_to'=>$id,'sent_flag'=>'1','read_flag'=>0])->where('deleted_flag','!=','2')->where('deleted_flag','!=','3')->get();
			
			return json_encode(array('sent'=>count($sent),'draft'=>count($draft),'inbox'=>count($inbox),'unread'=>count($unread)));
		   
		}
}

