<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use DB;
class PrintController extends Controller
{
   public function printNewKot($KotNo,$locationcode){
	   date_default_timezone_set("Asia/Kolkata");
	   $kotitems = DB::table('KOTMaster')->where('KOTNo',$KotNo)->get();
	   $Location = DB::table('LocationMaster')->where('LocationCode',$locationcode)->first();
	   $kitchenMaster = DB::table('KitchenMaster')->get();
	   foreach($kotitems as $key=>$value){
		   $items[$key] = array( new item($value->MenuItemName,$value->Quantity));
		   foreach($kitchenMaster as $key=>$kitchen){
			   if($kitchen->KitchenCode==$value->KitchenCode){
				   
				   $kitchenwise[$kitchen->KitchenName][$value->MenuItemName] = array( new item($value->MenuItemName,$value->Quantity));
				   
				   //$kPrinterIp[$kitchen->KitchenName] =$kitchen->KitchenPrinterIP;
				   $kPrinterIp[$kitchen->KitchenName][0] =$kitchen->KitchenPrinterIP;
				   $kPrinterIp[$kitchen->KitchenName][1] =$kitchen->KitchenPrinterIP1;
				   $kPrinterIp[$kitchen->KitchenName][2] =$kitchen->KitchenPrinterIP2;
				   $kPrinterIp[$kitchen->KitchenName][3] =$kitchen->KitchenPrinterIP3;
				   $kPrinterIp[$kitchen->KitchenName][4] =$kitchen->KitchenPrinterIP4;
				    $modifire[$value->MenuItemName] = $value->Remarks;
				   $query = DB::statement('EXEC dbo.procTabKOTPrintUpdate ?,?,?,?,?',array($value->LocationCode,$value->MenuItemCode ,$value->MenuItemName,$value->KOTDate,$KotNo));
			   }
			   
		   }
		   
		   
	   }
	   
	  //$location = $kotitems[0]->KitchenName;
	  $captain = $kotitems[0]->CaptianName;
	  $stwd = $kotitems[0]->StewardName;
	  $tableNo = $kotitems[0]->TableNo;
	  $UserName1 = $kotitems[0]->UserName1;
	  $date = date('d-m-Y : H:m');
	  $title= "KOT";
	//  echo $date." ".$tableNo."".$stwd."".$captain."".$KotNo."".$Location->LocationName."".$UserName1; 
	  
	//print_r($kitchenwise);
	 $KOTC = DB::select('EXEC dbo.procTabGetTabParamMaster ?,?',array($locationcode,'KOTC'));
	 $numberofprinter = $KOTC[0]->ParameterValue;
	foreach($kitchenwise as $key=>$kitchenItem){
		//echo $kPrinterIp[$key];
		for($i=0;$i<$numberofprinter;$i++){
			if($kPrinterIp[$key][$i]!=""){
			$this->printKot($kPrinterIp[$key][$i],$Location->LocationName,$key,$KotNo,$captain,$stwd,$tableNo,$UserName1,$kitchenItem,$title,$modifire);
			}
	    }
	}
	
	
  }
  
  
  public function printModifedKot($kotItems){
	   date_default_timezone_set("Asia/Kolkata");
	   
	   $kitchenMaster = DB::table('KitchenMaster')->get();
	   foreach($kotItems as $key=>$value){
		   $items[$key] = array( new item($value->MenuItemName,$value->Quantity));
		   foreach($kitchenMaster as $key=>$kitchen){
			   if($kitchen->KitchenCode==$value->KitchenCode){
				   
				   $kitchenwise[$kitchen->KitchenName][$value->MenuItemName] = array( new Modifieditem($value->MenuItemName,$value->RejectionQuantity,$value->Quantity));
				   $kPrinterIp[$kitchen->KitchenName][0] =$kitchen->KitchenPrinterIP;
				   $kPrinterIp[$kitchen->KitchenName][1] =$kitchen->KitchenPrinterIP1;
				   $kPrinterIp[$kitchen->KitchenName][2] =$kitchen->KitchenPrinterIP2;
				   $kPrinterIp[$kitchen->KitchenName][3] =$kitchen->KitchenPrinterIP3;
				   $kPrinterIp[$kitchen->KitchenName][4] =$kitchen->KitchenPrinterIP4;
				   $modifire[$value->MenuItemName] = $value->Remarks;
				  
				   
				   $query = DB::select('EXEC dbo.procTabKOTPrintUpdate ?,?,?,?,?',array($value->LocationCode,$value->MenuItemCode ,$value->MenuItemName,$value->KOTDate,$KotNo));
			   }
			   
		   }
		   
		   
	   }
	   
	  $location = $kotitems[0]->KitchenName;
	  $captain = $kotitems[0]->CaptianName;
	  $stwd = $kotitems[0]->StewardName;
	  $tableNo = $kotitems[0]->TableNo;
	  $UserName1 = $kotitems[0]->UserName1;
	  $date = date('d-m-Y : H:m');
	  $title= "KOT";
	  echo $date." ".$tableNo."".$stwd."".$captain."".$KotNo."".$location."".$UserName1; 
	  
	//print_r($kitchenwise);
	   $KOTC = DB::select('EXEC dbo.procTabGetTabParamMaster ?,?',array($locationcode,'KOTC'));
	   $numberofprinter = $KOTC[0]->ParameterValue;
	foreach($kitchenwise as $key=>$kitchenItem){
		//echo $kPrinterIp[$key];
		for($i=0;$i<$numberofprinter;$i++){
			if($kPrinterIp[$key][$i]!=""){
			$this->printKot($kPrinterIp[$key][$i],$location,$key,$KotNo,$captain,$stwd,$tableNo,$UserName1,$kitchenItem,$title,$modifire);
			}
		}
		 
	}
	
	
  }
  
  
  
  
  
  
  public function printKot($PrinterIp,$location,$KitchenName,$KotNo,$CaptianName,$stwd,$TableNo,$UserName1,$items,$title, $modifire=[]){
	  $connector = new NetworkPrintConnector($PrinterIp, 9100);
	  //start printer
	  $printer = new Printer($connector);
      // set alignment
	  $printer -> setJustification(Printer::JUSTIFY_CENTER);
	  /* tittle of Reciept*/
	 $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
	 $printer -> text("HOTEL AUREOLE");
	 $printer -> selectPrintMode();
	 //$printer -> text("");
	 $printer -> feed();
	 $printer -> setEmphasis(true);
	 $printer -> text($title."\n");
	 $printer -> setEmphasis(false);
	 //kot Information;
	 
	 $printer -> setJustification(Printer::JUSTIFY_LEFT);
	 $printer -> setEmphasis(true);
	 $printer -> text("location : ".$location);
	 $printer -> text("Kitchen : ".$KitchenName);
	 $printer -> text("KOT : ".$KOTNo);
	 $printer -> text("Captian : ".$CaptianName);
	 $printer -> text("Stwd : ".$stwd);
	 $printer -> text("Table : ".$TableNo);
	 $printer -> setEmphasis(false);
	
		/* Items */
		$printer -> setJustification(Printer::JUSTIFY_LEFT);
		$printer -> setEmphasis(true);
		if($title!='KOT'){
			$printer -> text(new Modifieditem('Item','Rej.Qty','Qty'));
		}else{
			
			$printer -> text(new item('Item', 'Qty'));
		}
		
		$printer -> setEmphasis(false);
		foreach ($items as $item) {
				$printer -> text($item);
				if($title=='KOT'){
				$printer -> text($modifire[$item->name]);
				}
			}
		$printer -> setEmphasis(true);
		/* Footer */
		$printer -> feed(2);
		$printer -> setJustification(Printer::JUSTIFY_CENTER);
		$printer -> text($UserName1. '\t'. $date ." " .$KitchenName );
		$printer -> feed(2);
		$printer -> cut();
		$printer -> pulse();
		$printer -> close();
   
	  
	  
  }
   
   
   
   
   
   
   
} 

  
   /* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $quantity;
  

    public function __construct($name = '', $quantity = '')
    {
        $this -> name = $name;
        $this -> quantity = $quantity;
        
    }
    
    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        
        $left = str_pad($this -> name, $leftCols) ;
        
       // $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad( $this -> quantity, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
   
class Modifieditem
{
    private $name;
    private $Rej;
    private $Quantity;

    public function __construct($name = '', $Rej = '', $Quantity = '')
    {
        $this -> name = $name;
        $this -> rej = $Rej;
        $this -> quantity = $Quantity;
    }
    
    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 10;
		$middleCols = 5;
        
        $left = str_pad($this -> name, $leftCols) ;
        $middle = str_pad($this -> rej, $middleCols,' ',STR_PAD_BOTH) ;
        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$middle$right\n";
    }
}   
   

   
   

