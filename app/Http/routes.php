<?php

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

Route::post('/message', function()
{
  $picture = strtolower(trim(Input::get('Body')));

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api-http.littlebitscloud.cc/devices/".env('CLOUDBIT_ID')."/output");
  curl_setopt($ch, CURLOPT_POST, 1);

  if(strcmp($picture, "ghost") == 0){
    curl_setopt($ch, CURLOPT_POSTFIELDS, "duration_ms=2000&percent=75");
  } elseif (strcmp($picture, "bat") == 0) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, "duration_ms=2000&percent=25");
  } else {
    $resp = Response::make("<Response><Message>Sorry I don't know that one. Try bat or ghost.</Message></Response>", 200);
    $resp->header('Content-Type', 'text/xml');
    return $resp;
  }

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('CLOUDBIT_TOKEN')));
  $response = curl_exec($ch);
  curl_close($ch);

  $resp = Response::make("<Response><Message>Happy Halloween! I set the image on Brent's hackpack to a ".$picture.".</Message></Response>", 200);
  $resp->header('Content-Type', 'text/xml');
  return $resp;
});
