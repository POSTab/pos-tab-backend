<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use PDO;
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
	
	public function getTable($location){
		//first Updates Flag
		$updateFlag = DB::statement('EXEC dbo.procTabUpdateTableMasterStatus ?',array($location));
		
		$query  = DB::table('TableMaster')->where('LocationCode',$location)->orderby('TableNo','asc')->get();
		
		
		return json_encode($query);
	}
	
	public function getGuests(){
		$query  = DB::table('GUEST')->get();
		
		
		return json_encode($query);
	}
	public function getKotno($location){
		$query  = DB::table('KOTMaster')->max('KOTNo');
		//$query = DB::select('EXEC dbo.procTabKOTNoLast ?,?,?',array($location,date('Y-m-d'),0));
		//echo $query;
		if($query){
			return json_encode($query+1);
		}else{
			return json_encode(1);
		}
		
		//return json_encode($query);*/
	}
	 
    public function getKotnobyp($location){

		$out=null;
		$date = date('Y-m-d');
		$pdo = DB::connection()->getPdo();
	    $stmt = $pdo->prepare('DECLARE @KOTNO1 int; EXEC dbo.procTabKOTNoLast ?,?, @KOTNO1 OUTPUT; SELECT @KOTNO1 as KOTNO1;');	
		$stmt->bindParam(1,$location);
		$stmt->bindParam(2,$date);
		$stmt->execute();
		///ec/ho json_encode($stmt);
	    $statArr = array();
		do 
		{
			$statArr = $stmt->fetchAll(PDO::FETCH_ASSOC);

		} while ($stmt->nextRowset());
		
	     return $statArr[0]['KOTNO1'];
		}
		
		
    public function getOpenkots($location){
		//echo $location;
		//$amount = 0;
		//$tables = DB::SELECT('EXEC dbo.procTabOpenKOT ? ',array($location)); 
		$pdo = DB::connection()->getPdo();
	    $stmt = $pdo->prepare('EXEC dbo.procTabOpenKOT ?');	
		$stmt->bindParam(1,$location);
		//$stmt->bindParam(2,$date);
		$stmt->execute();
		///ec/ho json_encode($stmt);
	   // $tables = array();
		do 
		{
			$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

		} while ($stmt->nextRowset());
		
	    // return $statArr[0]['KOTNO1'];
		
		//print_r($tables);
		$kotno = array();
		$amount= array();
		foreach($tables as $table){
			
			//var_dump($table['TableNo']);
			$kots = DB::select('EXEC dbo.procTabOpenKOT_frmTable ?',array($table['TableNo']));
			//print_r($kots);
			$amountt = 0;
			$kotnoc = "";
 			foreach($kots as $Kot){
				
				 $amountt = $amountt + intval($Kot->TotalAmount);
				$kotnoc = $kotnoc."".$Kot->KOTNo.",";
			}
			
			$amount[$table['TableNo']] = $amountt;
			$kotno[$table['TableNo']] = $kotnoc;
		}
		
		return json_encode(array('tables'=>$tables,"amounts"=>$amount,"kots"=>$kotno));
		
		
	}
	
	public function getCommanParameter(){
		$valids = DB::select('EXEC dbo.procTabGetTabParamCommon ');
		$result=array();
		foreach($valids as $valid){
			
			$result[$valid->ParameterCode] = $valid->Applicable;
			
			
		}
		
		return json_encode($result);
		}
		
    public function getupdatetable(){
		$valids = DB::select('EXEC dbo.procTabGetTabParamCommon ');
		$result=array();
		foreach($valids as $valid){
			
			$result[$valid->ParameterCode] = $valid->Applicable;
			
			
		}
		
		return json_encode($result);
		}
}