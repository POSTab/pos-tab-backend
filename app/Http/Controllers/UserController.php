<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use App\User;
use App\Role;

class UserController extends Controller
{
   public function getUsers(){
       $users = User::where('active','!=','d')->get();
      // $users = User::find(1)->project()->orderBy('project_id')->get();
       return json_encode($users);
       
   }
   
   public function login(Request $request){
   $credentials = Input::only('email', 'password');
   $status=0;

   if ( ! $token = JWTAuth::attempt($credentials)) {
     //  return json_encode($credentials);
	    return json_encode(compact('status'));
   }
   $user = User::with('roles')
					->where('email',$request->input('email'))
					//->select('users.id','name','username','email','mobile','active','roles.id as role_id','rolename')
					->get();
	$status=1;
	$userlog = DB::table('userlog')->insert([
	  'email' => $request->input('email'),
      'token' => $token	,
      'created_at'=>date('Y-m-d H:m:s')	  
	]);
	
   return json_encode(compact('token','user','status'));

   }
   
   
   
   public function createUser(Request $request){
        $role[1]=Role::where('rolename','Admin')->first();
        $role[2]=Role::where('rolename','Manager')->first();
        $role[3]=Role::where('rolename','User')->first();
		
        $user = new User();
        $user->name = $request->input('name');
        $user->email =$request->input('email');
        $user->mobile = $request->input('mobile');
		$user->username=$request->input('username');
        $user->password = bcrypt($request->input('password'));
		$user->active=$request->input('active');
		if((User::where('email',$user->email)->count())==0)
        {$user->save();
        $user->roles()->attach($role[$request->input('userrole')]);
        
        return 1;
		}
		else{
			return 0;
		}
   }
   
   public function updateUser(Request $request){
      $userinfo = $request->input();
	  $userId =  $request->input('id');
       $query = User::where('id', $userId);
	   if($request->input('password')){
		   $query->update([
		      'name'=> $request->input('name'),
			   'email' => $request->input('email'),
			   'mobile'=>  $request->input('mobile'),
			   'password'=> $request->input('password'),
			   'active'=> $request->input('active'),
		       'updated_at'=> date('Y-m-d') 
		   ]);
	   }else{
		   $query->update([
		      'name'=> $request->input('name'),
			   'email' => $request->input('email'),
			   'mobile'=>  $request->input('mobile'),
			   'active'=> $request->input('active'),
		       'updated_at'=> date('Y-m-d') 
		   ]);
	   }
        $role=$request->input('userrole');
       $db = DB::table('user_role')->where('user_id',$userId)->update(['role_id'=>$role]);		
       return $role;
   }
   
   public function deleteUser($id){
	    if((DB::table('user_project')->where('user_id',$id)->count())==0){
			 $query = User::where('id', $id)
            ->update(['active' => 'd']);
           return $query;
			
		}else{
			return 0;
		}
      
   }
   
   public function getUserdetail($id){
       $userinfo = User::with('roles')->find($id);
     
       $project = User::find($id)->project()->orderBy('project_id')->get();
     
       return json_encode(array('userinfo'=> $userinfo, 'project'=>$project));
   }
  

  public function subscribe(Request $request){
        $userId = $request->input('user');
        $gcm_reg_id = $request->input('token');
							
    
					  $type = DB::table('push_id')
						->where('user_id',$userId)
						->first();
						if($type)
						{
							//update device Id
							$push_id = DB::table('push_id')
											->where('user_id',$userId)
											->update(['device2' =>$gcm_reg_id]);
							
						}
						else
						{
							$push_id = DB::table('push_id')->insertGetId(
							 ['user_id' => $userId, 
							 'device1' =>$gcm_reg_id ,
							 'device2' =>"" ]);				
						}
                                              
											  return json_encode($push_id);
					
         
    }
	
	
	public function getUsercount(){
		$user = User::all();
		return count($user);
		
	}
    
}
