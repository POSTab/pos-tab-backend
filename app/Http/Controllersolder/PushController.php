<?php namespace App\Http\Controllers;
use DB;
use \Input;
use DOMPDF;
use App\Http\Controllers\Controller;

class PushController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| List Controller
	|--------------------------------------------------------------------------
	|
	| This List Controller is used for Listing  
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		
		$this->middleware('guest');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	
	public function index()
	{
		return view('home');
	}
   public static function send_notification($registatoin_ids, $message) {
        // include GOOGLE_API_KEY
    // define("GOOGLE_API_KEY", "AIzaSyDNjGLxT-zAAF2eWucKYZFwCWgdrcSUcJM"); 
 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
 
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
 
        $headers = array(
            'Authorization: key=AIzaSyDNjGLxT-zAAF2eWucKYZFwCWgdrcSUcJM',
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
       // echo $result;
    }

}


