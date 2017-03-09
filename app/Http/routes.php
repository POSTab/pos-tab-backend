<?php
/* CORS(Cross-origin resource sharing   Enable */
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT,DELETE');
  header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
  header('Access-Control-Allow-Credentials: true');
/*
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('hotsys/login','hotsysController@login');
Route::get('hotsys/getshift','ListController@getShift');
Route::get('hotsys/getmeal','ListController@getMeal');
Route::get('hotsys/getlocation','ListController@getLocation');
Route::get('hotsys/getCaptain','ListController@getCaptain');
Route::get('hotsys/getSteward','ListController@getSteward');
Route::get('hotsys/getDept','ListController@getDept');
Route::get('hotsys/getEmply','ListController@getEmply');
Route::get('hotsys/getReason','ListController@getReason');
Route::get('hotsys/getMenucat/{location}','ListController@getMenucat');
Route::get('hotsys/getMenus/{menucategory}','ListController@getMenus');
Route::get('hotsys/checkTable/{tableno}','ListController@checkTable');
Route::get('hotsys/getTable/{location}','ListController@getTable');
Route::get('hotsys/getTable','ListController@getTable');
Route::get('hotsys/getGuests','ListController@getGuests');
Route::get('hotsys/getKotno/{location}','ListController@getKotno');
Route::get('hotsys/getKotnobyp/{location}','ListController@getKotnobyp');
Route::get('hotsys/getOpenkots/{location}','ListController@getOpenkots');
Route::get('hotsys/getCommanParameter','ListController@getCommanParameter');
Route::get('hotsys/printbill','PrintController@printbill');
Route::post('hotsys/modifykot','hotsysController@modifykot');
Route::post('hotsys/saveKot','KotController@saveKot');
Route::get('hotsys/openKot/{tableNo}','KotController@getOpenKots');
Route::get('hotsys/getTaxes/{location}','BillController@getTaxes');
Route::get('hotsys/getDiscount','BillController@getDiscount');
Route::get('hotsys/saveBill','BillController@saveBill');
Route::get('hotsys/printnewkot/{kotno}/{location}','PrintController@printNewKot');



Route::post('/signup', function (Request $request) {
   $credentials = Input::only('email', 'password');

   try {
       $user = User::create($credentials);
   } catch (Exception $e) {
       return Response::json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
   }

   $token = JWTAuth::fromUser($user);

   return Response::json(compact('token'));
});


// for defmo print
Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::get('home/index', 'HomeController@index');
Route::get('home/samples', 'HomeController@samples');
Route::get('DemoPrintFile', 'DemoPrintFileController@index');
Route::get('DemoPrintFileController', 'DemoPrintFileController@printFile');
Route::get('DemoPrintCommands', 'DemoPrintCommandsController@index');
Route::get('DemoPrintCommandsController', 'DemoPrintCommandsController@printCommands');
Route::get('WebClientPrintController', 'WebClientPrintController@processRequest');