<?php

namespace App\Http\Controllers;
use App\Http\Controllers\PrintController;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use PDO;
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
		//echo  $captainName."".$captaincode;
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
		$out='';
		//$query  = DB::INSERT('EXEC procTabSaveKOTDetail ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($locationcode,$menuItemcode,$Quantity,$tableNo,$kotNumber,$complementaryKot, $shiftNo,$mealCode,$departmentcode,$EmployeCode,$covers,$captaincode,$captainName,$stewardCode,$stewardName,$NADT,$MenulocationCode,$username,$kotListarray,$complementaryReasoncode,$guest,$Kotmodified_flag,$RejQuatity,$MenuRemark,$RejReason,$totalRate,$menuItemName,$RoomNo,$customerCode,$BNAQTYPE,$BNAQFOLIO,$BNAQCONAME,$MembershipCode,$MembershipName,$MembershipType,$RoomFolio,$RoomGuest,$MenuComboBuffet,$MenuComboBuffetCode,$MacId,0));
		//$query  = DB::INSERT('EXEC procTabSaveKOTDetail ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array('ROOM','GHER,','2,','003','5001','0', '3','1','NULL','NULL','2','0001','SUNNY','S001','BHOLA','2017-02-24','LUME,','MUZZU','4995,','NULL','NULL','Y,','1,','NULL','xyz,','0,','Gherkins-Add$$$','NULL','0','NULL','NULL','NULL','NULL','NULL','NULL','NULL','NULL','NULL','NULL','NULL',0));
		
		
		$pdo = DB::connection()->getPdo();
	    $stmt = $pdo->prepare('DECLARE @KotNo1 int;  EXEC dbo.procTabSaveKOTDetail ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@KotNo1 OUTPUT; SELECT @KOTNO1 as KOTNO1;');	
	
		$stmt->bindParam(1,$locationcode);
		$stmt->bindParam(2,$menuItemcode);
		$stmt->bindParam(3,$Quantity);
		$stmt->bindParam(4,$tableNo);
		$stmt->bindParam(5,$kotNumber);
		$stmt->bindParam(6,$complementaryKot);
		$stmt->bindParam(7,$shiftNo);
		$stmt->bindParam(8,$mealCode);
		$stmt->bindParam(9,$departmentcode);
		$stmt->bindParam(10,$EmployeCode);
		$stmt->bindParam(11,$covers);
		$stmt->bindParam(12,$captaincode);
		$stmt->bindParam(13,$captainName);
		$stmt->bindParam(14,$stewardCode);
		$stmt->bindParam(15,$stewardName);
		$stmt->bindParam(16,$NADT);
		$stmt->bindParam(17,$MenulocationCode);
		$stmt->bindParam(18,$username);
		$stmt->bindParam(19,$kotListarray);
		$stmt->bindParam(20,$complementaryReasoncode);
		$stmt->bindParam(21,$guest);
		$stmt->bindParam(22,$Kotmodified_flag);
		$stmt->bindParam(23,$RejQuatity);
		$stmt->bindParam(24,$MenuRemark);
		$stmt->bindParam(25,$RejReason);
		$stmt->bindParam(26,$totalRate);
		$stmt->bindParam(27,$menuItemName);
		$stmt->bindParam(28,$RoomNo);
		$stmt->bindParam(29,$customerCode);
		$stmt->bindParam(30,$BNAQTYPE);
		$stmt->bindParam(31,$BNAQFOLIO);
		$stmt->bindParam(32,$BNAQCONAME);
		$stmt->bindParam(33,$MembershipCode);
		$stmt->bindParam(34,$MembershipName);
		$stmt->bindParam(35,$MembershipType);
		$stmt->bindParam(36,$RoomFolio);
		$stmt->bindParam(37,$RoomGuest);
		$stmt->bindParam(38,$MenuComboBuffet);
		$stmt->bindParam(39,$MenuComboBuffetCode);
		$stmt->bindParam(40,$MacId);
        //$stmt->bindParam(41, $out);
        //$stmt->bindParam('', $out);
		$stmt->bindColumn('@KOTNO1', $KOTNO1);
	
	     $stmt->execute();
	    $statArr = array();
	//	$stmt->nextRowset();
	  $statArr = $stmt->fetchAll();
	 //  
	  // do 
		//{	
		// $statArr = $stmt->fetch(PDO::FETCH_ASSOC);

		//} while ($stmt->nextRowset());
		
	    echo "outparam".$KOTNO1;
		print_r($statArr);

		
		if($kotItem != null){
			$pkk = DB::select('EXEC dbo.procTabGetTabParamMaster ?,?',array($locationcode,'PKK'));
			if($pkk=="Y"){
				(new PrintController)->printNewKot($kotNumber,$locationcode);
			}
			
			
		}
		if($openkots != null){
			$pkk = DB::select('EXEC dbo.procTabGetTabParamMaster ?,?',array($locationcode,'PKK'));
			if($pkk=="Y"){
			(new PrintController)->printModifedKot($openkots);
			}
		}
		
		//if($query){
		//	 (new PrintController)->printbill();
		//	return json_encode(array('status'=>'1','userInfo'=>$query));
			
		//}
		//else{
		//	return json_encode(array('status'=>'0'));
		//}
		
		
	}
	
	public function	getOpenKots($tableNo){
	 
		$query  = DB::SELECT('EXEC procTabOpenKOT_frmTable ? ' ,array($tableNo));
	     return json_encode($query);
	}
	
	
	
	
}
