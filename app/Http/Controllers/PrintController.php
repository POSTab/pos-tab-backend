<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
   public function printbill(){
	   echo "printing log";
	    $connector = new NetworkPrintConnector("192.168.1.81", 9100);
		  
/* Information for the receipt */
$items = array(
    new item("Example item #1", "4.00"),
    new item("Another thing", "3.50"),
    new item("Something else", "1.00"),
    new item("A final item", "4.45"),
);
$subtotal = new item('Subtotal', '12.95');
$tax = new item('A local tax', '1.30');
$total = new item('Total', '14.25', true);
/* Date is kept the same for testing */
// $date = date('l jS \of F Y h:i:s A');
$date = "Monday 6th of April 2015 02:56:25 PM";

/* Start the printer */
//$logo = EscposImage::load("resources/escpos-php.png", false);
$printer = new Printer($connector);

/* Print top logo */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
//$printer -> graphics($logo);

/* Name of shop */
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text("ExampleMart Ltd.\n");
$printer -> selectPrintMode();
$printer -> text("Shop No. 42.\n");
$printer -> feed();

/* Title of receipt */
$printer -> setEmphasis(true);
$printer -> text("SALES INVOICE\n");
$printer -> setEmphasis(false);

/* Items */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$printer -> text(new item('', '$'));
$printer -> setEmphasis(false);
foreach ($items as $item) {
    $printer -> text($item);
}
$printer -> setEmphasis(true);
$printer -> text($subtotal);
$printer -> setEmphasis(false);
$printer -> feed();

/* Tax and total */
$printer -> text($tax);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text($total);
$printer -> selectPrintMode();

/* Footer */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Thank you for shopping at ExampleMart\n");
$printer -> text("For trading hours, please visit example.com\n");
$printer -> feed(2);
$printer -> text($date . "\n");

/* Cut the receipt and open the cash drawer */
$printer -> cut();
$printer -> pulse();

$printer -> close();
		/* //Create ESC/POS commands for sample receipt
            $esc = '0x1B'; //ESC byte in hex notation
            $newLine = '0x0A'; //LF byte in hex notation
            
            $cmds = '';
            $cmds = $esc . "@"; //Initializes the printer (ESC @)
            $cmds .= $esc . '!' . '0x38'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
            $cmds .= 'TAB SYS KOT '; //text to print
            $cmds .= $newLine . $newLine;
            $cmds .= $esc . '!' . '0x00'; //Character font A selected (ESC ! 0)
            $cmds .= 'TEA                   5.00'; 
            $cmds .= $newLine;
            $cmds .= 'MILK 65 Fl oz             3.78';
            $cmds .= $newLine . $newLine;
            $cmds .= 'SUBTOTAL                  8.78';
            $cmds .= $newLine;
            $cmds .= 'TAX 5%                    0.44';
            $cmds .= $newLine;
            $cmds .= 'TOTAL                     9.22';
            $cmds .= $newLine;
            $cmds .= 'CASH TEND                10.00';
            $cmds .= $newLine;
            $cmds .= 'CASH DUE                  0.78';
            $cmds .= $newLine . $newLine;
            $cmds .= $esc . '!' . '0x18'; //Emphasized + Double-height mode selected (ESC ! (16 + 8)) 24 dec => 18 hex
            $cmds .= '# ITEMS SOLD 2';
            $cmds .= $esc . '!' . '0x00'; //Character font A selected (ESC ! 0)
            $cmds .= $newLine . $newLine;
            $cmds .= '11/03/13  19:53:17';
		
		//$connector = new FilePrintConnector("php://stdout");
		//echo $cmds;
		$printer = new Printer($connector);
		$printer -> text($cmds);
		$printer -> cut();
		$printer -> close();
	     print_r($printer);*/
   }
}   
   /* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }
    
    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;
        
        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
   

