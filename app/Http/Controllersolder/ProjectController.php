<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Project;
use App\User;
use Symfony\Component\Console\Input\Input;
use JWTAuth;
use DB;
class ProjectController extends Controller
{
    //
	public function _construct(){
		$this->middleware('jwt-auth');
		date_default_timezone_set("Asia/Kolkata");
		
	}
    
    public function getproject(){
        $project = Project::where('flag','!=','d')->get();
        echo json_encode($project);
    }
    
      public function createProject(Request $request){
          
        $project = new Project();
        $project->projectname = $request->input('projectname');
        $project->flag =1;
        $project->created_at = date('Y-m-d');
	$project->updated_at=date('Y-m-d');
        
        $create=$project->save();
       
        
        return json_encode($create);
   }
   
   public function updateProject($id){
       
       $project = Project::where('id', 2)
            ->update(['projectname' => 'Curry Rd']);
       return $project;
   }
   
   public function deleteProject($id){
        $project = Project::where('id', $id)
            ->update(['flag' => 'd']);
       return $project;
   }
   
  public function getProjectdetails($id){
       
       $projectinfo = Project::find($id);
     
       $users = Project::find($id)->user()->orderBy('project_id')->get();
     
       return json_encode(array('projectinfo'=> $projectinfo, 'Users'=>$users));
       
   }
   
   public function getUsers(Request $request){
	   $ids = $request->input('ids');
	   $except = explode(',',$ids);
	     $users = User::whereNotIn('id',$except)->where('active','!=','d')->get();
		 return json_encode($users);
   }
   
   public function assignUser($id , Request $request){
	   
           $userid = $request->input('data'); 
		   $userid = explode(',',$userid);
		
	      for($i=0;$i<count($userid);$i++){
			  
			  $query = DB::table('user_project')->insert([
			    'user_id'=>$userid[$i],
				'project_id'=>$id,
				'created_at'=>date('Y-m-d')
			  ]);
		  }
	   
	     return 1;
	   
   }
   
   public function removeUser(Request $request){
	    $userid = $request->input('userid'); 
		 $proid = $request->input('proid'); 
	    $query = DB::table('user_project')->where([
			    'user_id'=>$userid,
				'project_id'=>$proid,
				  ])->delete();
	   return $userid;
   }
   
   public function getprojectCount(){
	   $project = Project::all();
	   
	   return count($project);
	   
	   
   }
   
}
