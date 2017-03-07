<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use PDO;
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
   
   public function saveBill(Request $request){
	   	$data['ComplimentaryBillFlag'] = false;
		$data['strSQL'] ='';// "select LocationCode, MenuItemCode, TableNo, RoomNo, KOTDate, KOTNo, BillNo, ComplimentaryNo, ShiftNo, MealCode, EmployeeCode, EmployeeName, EmployeeMonthlyLimit, EmployeeYearlyLimit, DepartmentCode, DepartmentName, DepartmentMonthlyLimit, DepartmentYearlyLimit,ReasonCode, ReasonDescription, ReasonMonthlyLimit, ReasonYearlyLimit, Guest, MenuItemName, Covers, Quantity, Rate, CategoryCode,CategoryName, KitchenCode, KitchenName, ItemTypeCode, ItemTypeDescription, MenuTypeCode, MenuTypeDescription, MenuTypeCode2,MenuTypeDescription2, WithTax, Tax1, Tax2, Tax3, Tax4, Tax5, Tax6, Tax7, Tax8, Tax9, Tax10, Tax1Amount, Tax2Amount, Tax3Amount, Tax4Amount,Tax5Amount, Tax6Amount, Tax7Amount, Tax8Amount, Tax9Amount, Tax10Amount, NettAmount, DiscountPercent, FoodDiscount, LiquorDiscount,BeverageDiscount, TobaccoDiscount, TotalAmount, RoundOff, MenuLocationCode, BillLocationCode, BillSplitNo, RejectionQuantity, RejectionReason,Remarks, CaptianCode, CaptianName, StewardCode, StewardName, Status, Settlement, CustomerCode, KOTPrintFlag, KOTNoOfCopy, KOTPrintTime,KOTModifyFlag, KOTCancelFlag, KOTPrintTimeLast, Month01, Month02, Month03, Month04, Month05, Month06, Month07, Month08, Month09, Month10,Month11 , Month12, UserName1, NADT, EntryDate, EntryTime, EditedBy, EditDate, EditTime, ComplimentaryKOT ,[BANQType],[BANQFolio],[BANQCoName],[MembershipCode],[MembershipName],[MembershipType],[RoomFolio] ,[RoomGuest] from KOTMaster  Where (LocationCode = 'LUME' AND TableNo = '002'  AND Status = 0 AND Quantity > 0  AND BillSplitNo = 0 )";
		$data['StationerySize'] = '5';
		$data['BillTableNo'] =  '002';
		$data['BillLocationCode'] =  'ROOM';
		$data['KOTLocationCode'] =  'ROOM';
		$data['RoomNo']=  '';
		$data['FolioNo'] =  '';
		$data['NADT'] = '2017-02-24';
		$data['FoodDiscountPerc'] = 0;
		$data['BeverageDiscountPerc'] = 0;
		$data['LiquorDiscountPerc'] = 0;
		$data['TobaccoDiscountPerc'] = 0;
		$data['FHRAI'] = false;
		$data['CDApplicable'] = false;
		$data['FoodDiscount'] = 0;
		$data['BeverageDiscount'] = 0;
		$data['LiquorDiscount'] = 0;
		$data['TobaccoDiscount'] = 0;
		$data['DiscountBy'] =  '';
		$data['LastPrintDate'] = '2017-02-07 09:50:33 AM';
		$data['LastPrintTime'] = '2017-02-07 09:50:33 AM';
		$data['LastUserName'] =  'Admin';
		$data['BillPrintTime'] = '2017-02-07 09:50:33 AM';
		$data['UserName'] =  'Admin';
		$data['EntryDate'] = '2017-02-07 09:50:33 AM';
		$data['PrintoutNo'] = 1;
		$data['IsSettle'] = false;
		$data['PermitHolderNo'] =  '';
		$data['MembershipCode'] =  '';
		$data['PaymentMode1'] =  '';
		$data['PaymentMode1Amount'] = 0;
		$data['TipsAmount'] = 0;
		$data['CreditCardCode1'] =  '';
		$data['CreditCardNo1'] =  '';
		$data['DebtorsName1'] =  '';
		$data['Tax1Applicable'] = False;
		$data['Tax2Applicable'] = True;
		$data['Tax3Applicable'] = False;
		$data['Tax4Applicable'] = false;
		$data['Tax5Applicable'] = false;
		$data['TaxableFoodNettAmount'] = 0;
		$data['NonTaxableFoodNettAmount'] = 0;
		$data['TaxableBeverageNettAmount'] = 0;
		$data['NonTaxableBeverageNettAmount'] = 0;
		$data['TaxableLiquorNettAmount'] = 0;
		$data['NonTaxableLiquorNettAmount'] = 0;
		$data['TaxableTobaccoNettAmount'] = 0;
		$data['NonTaxableTobaccoNettAmount'] = 0;
		$data['CustomerCode'] = 0;
		$data['BANQType'] =  '';
		$data['BANQFolio'] =  '';
		$data['BANQCoName'] =  '';
		$data['ROOMGuest'] =  'MR. REGAN KEITH JASMIN';
		$data['BANQVENUEName'] = '';
		$data['Loyalty_MembCardType']= '';
		$data['Loyalty_MembCardNo']= '';
		$data['Loyalty_MembCardGSTName'] = '';
		$data['Loyalty_MembCardDiscType'] = '';
		$data['Loyalty_MembCardDiscPerc'] =0;
		$data['Loyalty_MembCardDiscAmt']=0;
		$data['MacID'] =  '';
		$data['BillNo1'] = 0;
		$data['ComplimentaryNo1'] = 0;
		//$count = 1;
		
		//$query = DB::INSERT('EXEC dbo.procTabSaveBillDetails ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$data);	
		//$query = DB::statement('EXEC dbo.procTabSaveBillDetails ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?' , array(false,'NULL','5','002','ROOM','ROOM','N','N','2017-02-24',0,0,0,0,false,false,0,0,0,0,'NULL','2017-02-07 09:50:33 AM','2017-02-07 09:50:33 AM','Admin','2017-03-07 09:50:33 AM','Admin','2017-03-07 09:50:33 AM',1,false,'NULL','NULL','NULL',0,0,'NULL','NULL','NULL',false,true,false,false,false,0,0,0,0,0,0,0,0,0,'NULL','NULL','NULL','MR. REGAN KEITH JASMIN','NULL','NULL','NULL','NULL','NULL',0,0,'NULL','NULL','NULL'));
		// echo $query;
	$pdo = DB::connection()->getPdo();
	$stmt = $pdo->prepare('DECLARE @BillNo1 int ,@ComplimentaryNo1 int;  EXEC dbo.procTabSaveBillDetails ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@BillNo1 OUTPUT,@ComplimentaryNo1 OUTPUT');	
	
		$stmt->bindParam(1,$data['ComplimentaryBillFlag']);
		$stmt->bindParam(2,$data['strSQL']);
		$stmt->bindParam(3,$data['StationerySize']);
		$stmt->bindParam(4,$data['BillTableNo']);
		$stmt->bindParam(5,$data['BillLocationCode']);
		$stmt->bindParam(6,$data['KOTLocationCode']);
		$stmt->bindParam(7,$data['RoomNo']);
		$stmt->bindParam(8,$data['FolioNo']);
		$stmt->bindParam(9,$data['NADT']);
		$stmt->bindParam(10,$data['FoodDiscountPerc']);
		$stmt->bindParam(11,$data['BeverageDiscountPerc']);
		$stmt->bindParam(12,$data['LiquorDiscountPerc']);
		$stmt->bindParam(13,$data['TobaccoDiscountPerc']);
		$stmt->bindParam(14,$data['FHRAI']);
		$stmt->bindParam(15,$data['CDApplicable']);
		$stmt->bindParam(16,$data['FoodDiscount']);
		$stmt->bindParam(17,$data['BeverageDiscount']);
		$stmt->bindParam(18,$data['LiquorDiscount']);
		$stmt->bindParam(19,$data['TobaccoDiscount']);
		$stmt->bindParam(20,$data['DiscountBy']);
		$stmt->bindParam(21,$data['LastPrintDate']);
		$stmt->bindParam(22,$data['LastPrintTime']);
		$stmt->bindParam(23,$data['LastUserName']);
		$stmt->bindParam(24,$data['BillPrintTime']);
		$stmt->bindParam(25,$data['UserName']);
		$stmt->bindParam(26,$data['EntryDate']);
		$stmt->bindParam(27,$data['PrintoutNo']);
		$stmt->bindParam(28,$data['IsSettle']);
		$stmt->bindParam(29,$data['PermitHolderNo']);
		$stmt->bindParam(30,$data['MembershipCode']);
		$stmt->bindParam(31,$data['PaymentMode1']);
		$stmt->bindParam(32,$data['PaymentMode1Amount']);
		$stmt->bindParam(33,$data['TipsAmount']);
		$stmt->bindParam(34,$data['CreditCardCode1']);
		$stmt->bindParam(35,$data['CreditCardNo1']);
		$stmt->bindParam(36,$data['DebtorsName1']);
		$stmt->bindParam(37,$data['Tax1Applicable']);
		$stmt->bindParam(38,$data['Tax2Applicable']);
		$stmt->bindParam(39,$data['Tax3Applicable']);
		$stmt->bindParam(40,$data['Tax4Applicable']);
		$stmt->bindParam(41,$data['Tax5Applicable']);
		$stmt->bindParam(42,$data['TaxableFoodNettAmount']);
		$stmt->bindParam(43,$data['NonTaxableFoodNettAmount']);
		$stmt->bindParam(44,$data['TaxableBeverageNettAmount']);
		$stmt->bindParam(45,$data['NonTaxableBeverageNettAmount']);
		$stmt->bindParam(46,$data['TaxableLiquorNettAmount']);
		$stmt->bindParam(47,$data['NonTaxableLiquorNettAmount']);
		$stmt->bindParam(48,$data['TaxableTobaccoNettAmount']);
		$stmt->bindParam(49,$data['NonTaxableTobaccoNettAmount']);
		$stmt->bindParam(50,$data['CustomerCode']);
		$stmt->bindParam(51,$data['BANQType']);
		$stmt->bindParam(52,$data['BANQFolio']);
		$stmt->bindParam(53,$data['BANQCoName']);
		$stmt->bindParam(54,$data['ROOMGuest']);
		$stmt->bindParam(55,$data['BANQVENUEName']);
		$stmt->bindParam(56,$data['Loyalty_MembCardType']);
		$stmt->bindParam(57,$data['Loyalty_MembCardNo']);
		$stmt->bindParam(58,$data['Loyalty_MembCardGSTName']);
		$stmt->bindParam(59,$data['Loyalty_MembCardDiscType']);
		$stmt->bindParam(60,$data['Loyalty_MembCardDiscPerc']);
		$stmt->bindParam(61,$data['Loyalty_MembCardDiscAmt']);
		$stmt->bindParam(62,$data['MacID']);
	//	$stmt->bindParam(63,$data['BillNo1']);
		//$stmt->bindParam(64,$data['ComplimentaryNo1']);
		
		
	
	   $stmt->execute();
	 //  dd($stmt);
	   $stmt->nextRowset();
	   
	  $x =  $stmt->fetchAll();
	
	   $statArr = array();
		//echo $stmt;
		//do{
		//	 $statArr =  $stmt->fetchAll();
		//	 }while ($stmt->nextRowset());
			
	  print_r($x);
	  
	  //print_r($statArr);
	   // return $statArr[0]['BillNo1'];
   
	   
	   
   }
   
   
   
   
   
}
