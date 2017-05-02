<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class hotsysController extends Controller
{
	
	
	
    public function login(Request $request){
		 
		 $username = $request->input('email');
		 $password = $request->input('password');
		 
		//$query = DB::select("EXEC userlogin($username, $password)");
		$query  = DB::select('EXEC dbo.procTabVerifyUser ?,?,?' ,array($username,$password,""));
		//$query  = DB::select('EXEC dbo.procVerifyUser "vishal", "123456"," "');
		if(count($query)==1){
			
			return json_encode(array('status'=>'1','userInfo'=>$query));
		}
		else{
			return json_encode(array('status'=>'0'));
		}
		
		
	}

     public function modifykot(Request $request){
		 
		 $id = $request->input('id');
		 $rate = $request->input('rate');
		 $modifyflag = $request->input('modifyflag');
		 $rejectedQty = $request->input('rejectedQty');
		 $varx=$request->input();
		  echo json_encode(array("input1"=>$varx,"input2"=>$varx));
		 
		//$query = DB::select("EXEC userlogin($username, $password)");
		$query  = DB::UPDATE('EXEC prcTabUpdateKOT ?,?,?,?,?,?' ,array($id,'2','2', $rejectedQty,'EXPRIED','REJECTED'));
		//$query  = DB::select('EXEC dbo.procVerifyUser "vishal", "123456"," "');
		if(count($query)==1){
			
			return json_encode(array('status'=>'1','userInfo'=>$query));
		}
		else{
			return json_encode(array('status'=>'0'));
		}
		
		
	}
	
	
		
   public function saveNprintKOT(Request $resquest){
		
		
		
		
	}
	
 	public function cancelKOT(Request $resquest){
		
		
		
		
	}
	
	public function cancelNprintKOT(Request $resquest){
		
		
		
		
	}
	
	
}
