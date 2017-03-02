<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
class BillController extends Controller
{
   public function getTaxes($location){
	   
	   $query= DB::select('EXEC dbo.procTabGetTaxStucture ?',array($location));
	   
	   return json_encode($query);
	   
	   
   }
   public function getDiscount(Request $request){
	    
		$parameter = $request->input('param');
		$location = $request->input('location');
		//echo $location;
 	    if($parameter=='FHRAI'){
			$Paramarray = ['FHFD','FHBD','FHTD','FHLD'];
			for($i=0;$i<count($Paramarray);$i++){
			$query[$Paramarray[$i]] = DB::select('EXEC dbo.procTabGetTabParamMaster ?,?',array($location,$Paramarray[$i]));
			}
			/*for($i=0;$i<count($Paramarray);$i++){
			$query[$i] = DB::select('EXEC dbo.procTabGetTabParamMaster ?,?',array($location,$Paramarray[$i]));
			}*/
			return json_encode($query);
		}
		else{
			$query = DB::select('EXEC dbo.procTabGetTabParamMaster ?,?',array($location,$parameter));
			
			return json_encode($query);
		}
	   
	   
	   
	   
   }
}
