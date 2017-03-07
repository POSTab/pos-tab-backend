<?php

namespace App\Http\Controllers;
use App\Http\Controllers\PrintController;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class KotController extends Controller
{
    public function saveKot(Request $request){
		
		//echo json_encode($request->input());
		
		$locationcode = $request->input('locationcode');
		$kotItem = $request->input('KotItem');
		$openkots = $request->input('openKots');
		$tableNo = $request->input('TableNo');
		$kotNumber = $request->input('KOTNo');
		$complementaryKot = $request->input('complementoryKot');
		$shiftNo = $request->input('shiftNo');
		$mealCode = $request->input('meal');
		$departmentcode = $request->input('departmentcode');
		$EmployeCode = $request->input('EmployeCode');
		$covers = $request->input('covers');
		$captain = $request->input('captain');
		$steward = $request->input('stewardcode');
		$captainName = $captain['EmployeeName'];
		$captaincode = $captain['EmployeeCode'];
		echo  $captainName."".$captaincode;
		$stewardName= $steward['EmployeeName'];
		$stewardCode= $steward['EmployeeCode'];
		$NADT =  date('Y-m-d');
		$MenulocationCode="";
		$menuItemcode="";
		$menuItemName="";
		$kotListarray="";
		$Quantity="";
		$MenuRemark="";
		$totalRate="";
		$totalRate="";
		$Kotmodified_flag="";
		$RejQuatity="";
		$RejReason="";
		$MenuComboBuffet=' ';
		$MenuComboBuffetCode = ' ';
		if($openkots != null){
		for($i=0; $i<count($openkots);$i++){
		$MenulocationCode = $MenulocationCode."".$openkots[$i]['MenuLocationCode'].",";
		$menuItemcode = $menuItemcode."".$openkots[$i]['MenuItemCode'].",";
		$menuItemName = $menuItemName."".$openkots[$i]['MenuItemName']."$$$";
		$kotListarray = $kotListarray."".$openkots[$i]['KOTNo'].",";
		$Quantity = $Quantity."".intval($openkots[$i]['Quantity']).",";
		//$MenuRemark = $MenuRemark."".$openkots[$i]['Remarks'].",";
		$MenuRemark = $MenuRemark."NULL,";
		$totalRate = $totalRate."".intval($openkots[$i]['NettAmount']).",";
		$Kotmodified_flag = $Kotmodified_flag."".$openkots[$i]['KOTModifyFlag'].",";
		//$RejQuatity = $RejQuatity."1,";
		$RejQuatity = $RejQuatity."".$openkots[$i]['RejectionQuantity'].",";
		$RejReason = $RejReason."".$openkots[$i]['RejectionReason'].",";
        $MenuComboBuffet= $MenuComboBuffet.',';
		$MenuComboBuffetCode = $MenuComboBuffetCode.',';
		}
		}
		if($kotItem != null){
		for($i=0; $i<count($kotItem);$i++){
		$MenulocationCode = $MenulocationCode."".$kotItem[$i]['LocationCode'].",";
		$menuItemcode = $menuItemcode."".$kotItem[$i]['MenuItemCode'].",";
		$menuItemName = $menuItemName."".$kotItem[$i]['MenuItemName']."$$$";
		$kotListarray = $kotListarray."".$kotItem[$i]['Kotno'].",";
		$Quantity = $Quantity."".$kotItem[$i]['qty'].",";
		$MenuRemark = $MenuRemark."".$kotItem[$i]['modifier'].",";
		$totalRate = $totalRate."".$kotItem[$i]['amount'].",";
		$Kotmodified_flag = $Kotmodified_flag."N,";
		$RejQuatity = $RejQuatity."".$kotItem[$i]['RejQuatity'].",";
		$RejReason = $RejReason."".$kotItem[$i]['RejReason'].",";
        $MenuComboBuffet= $MenuComboBuffet.',';
		$MenuComboBuffetCode = $MenuComboBuffetCode.',';
		}
		}
		//echo $menuItemcode."|".$menuItemName." |".$kotListarray." | ".$Quantity."|".$MenuRemark;
		$username = $request->input('username');
		if($complementaryKot==0 ){
			$complementaryReasoncode = '';  
		    $guest= '';
		}else{
			$complementaryReasoncode = $request->input('compresoncode'); 
			$guest= $request->input('guest');;
			
		}
		
		$customerCode = 0; 
		$RoomNo=$request->input('Room_No');
		$BNAQTYPE =  " ";
		$BNAQFOLIO = ' ';
		$BNAQCONAME = " ";
		$MembershipCode = " ";
		$MembershipName = " ";
		$MembershipType = " ";
		$RoomFolio = $request->input('Room_folio');
		$RoomGuest = $request->input('roomguest');

		$MacId = "0";
		
		$query  = DB::INSERT('EXEC procTabSaveKOTDetail ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($locationcode,$menuItemcode,$Quantity,$tableNo,$kotNumber,$complementaryKot, $shiftNo,$mealCode,$departmentcode,$EmployeCode,$covers,$captaincode,$captainName,$stewardCode,$stewardName,$NADT,$MenulocationCode,$username,$kotListarray,$complementaryReasoncode,$guest,$Kotmodified_flag,$RejQuatity,$MenuRemark,$RejReason,$totalRate,$menuItemName,$RoomNo,$customerCode,$BNAQTYPE,$BNAQFOLIO,$BNAQCONAME,$MembershipCode,$MembershipName,$MembershipType,$RoomFolio,$RoomGuest,$MenuComboBuffet,$MenuComboBuffetCode,$MacId,0));
		//$query  = DB::INSERT('EXEC procTabSaveKOTDetail ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array('ROOM','GHER,','2,','003','5001','0', '3','1','NULL','NULL','2','0001','SUNNY','S001','BHOLA','2017-02-24','LUME,','MUZZU','4995,','NULL','NULL','Y,','1,','NULL','xyz,','0,','Gherkins-Add$$$','NULL','0','NULL','NULL','NULL','NULL','NULL','NULL','NULL','NULL','NULL','NULL','NULL',0));
		
		
		

		
		
		
		
		if($query){
			 (new PrintController)->printbill();
			return json_encode(array('status'=>'1','userInfo'=>$query));
			
		}
		else{
			return json_encode(array('status'=>'0'));
		}
		
		
	}
	
	public function	getOpenKots($tableNo){
	 
		$query  = DB::SELECT('EXEC procTabOpenKOT_frmTable ? ' ,array($tableNo));
	     return json_encode($query);
	}
	
	
	
	
}
