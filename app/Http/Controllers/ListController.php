<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class ListController extends Controller
{
    public function getShift(){
		
		$query  = DB::select('EXEC dbo.procTabGetShift');
		return json_encode($query);
	}
	
	 public function getMeal(){
		
		$query  = DB::select('EXEC dbo.procTabGetMeal');
		return json_encode($query);
	}
	
	 public function getLocation(){
		$query  = DB::select('EXEC dbo.procTabLocation');
		//$query  = DB::table('LocationMaster')->get();
		return json_encode($query);
	}
	 public function getCaptain(){
		
		$query  = DB::table('EmployeeMaster')->where('EmployeeType','2')->get();
		return json_encode($query);
	}
	 public function getSteward(){
		
		$query  = DB::table('EmployeeMaster')->where('EmployeeType','3')->get();
		return json_encode($query);
	}
   public function getEmply(){
		
		$query  = DB::table('EmployeeMaster')->get();
		return json_encode($query);
	}
	 public function getDept(){
		
		$query  = DB::table('DepartmentMaster')->get();
		return json_encode($query);
	}
	
	 public function getReason(){
		
		$query  = DB::table('CompReasonMaster')->get();
		return json_encode($query);
	}
	
     public function getMenucat($location){
		
		$query  = DB::table('CategoryMaster')->where('LocationCode',$location)->get();
		return json_encode($query);
	}
	 public function getMenus($categorycode){
		if($categorycode==0){
			$query  = DB::table('MenuMaster')->get();
		 return json_encode($query);
		}else{
		$query  = DB::table('MenuMaster')->where('CategoryCode',$categorycode)->get();
		return json_encode($query);
		}
	}
	
	public function checkTable($tableno){
		$query  = DB::table('TableMaster')->where('TableNo',$tableno)->get();
		$OccupancyFlag = $query[0]->OccupancyFlag;
		
		return $OccupancyFlag;
	}
	
	public function getTable(){
		$query  = DB::table('TableMaster')->get();
		//$OccupancyFlag = $query[0]->OccupancyFlag;
		
		return json_encode($query);
	}
	
	public function getGuests(){
		$query  = DB::table('GUEST')->get();
		
		
		return json_encode($query);
	}
	public function getKotno(){
		$query  = DB::table('KOTMaster')->max('KOTNo');
		if($query){
			return json_encode($query+1);
		}else{
			return json_encode(1);
		}
		
		//return json_encode($query);
	}
    
}
