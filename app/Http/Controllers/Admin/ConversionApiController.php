<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ConversionApiController extends Controller
{
    public function endpoint()
    {
        return 'https://graph.facebook.com/v11.0/201006871905666/events?access_token=EAASINqRL4cUBAAyveHePkuE1Nihuf2yJJGuWh1YJ0ig58bL98sDNUkue8YbMZCKP3sRZCCLZB76j6NzPuehVeTrepFHu3sjrNXcYnXZBG5ehTrZCZAchZCQza7I3lVz5cF28zJkRIuDaiPuZActwsjXiTfJ6mzKitNdN5IY62sktAFcYCBtqDuwYoFBUshJPybgZD';
    }


    public function event($id,$qnty,$price,$link,$type){
        $data = [
            'data' => [
                [
                    'event_name' => $type,
                    'event_time' => strtotime('now'),
                    'user_data' => [
                        'em' =>[
                            hash('sha256', Auth::user()->email),
                        ],
                        'ph' =>[
                            hash('sha256', Auth::user()->phone),
                        ],
                        'client_ip_address' => request()->ip() ?? null,
                        'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        'fbc' => session('_fbc') ?? 'fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890'
                    ],
                    "contents" => [
                        [
                            "id" => $id,
                            "quantity" => $qnty,
                            "delivery_category" => "home_delivery"
                        ]
                    ],
                    "custom_data" => [
                        "currency" => "egp",
                        "value" => $price
                    ],
                    "event_source_url" => $link,
                    "action_source" => "website"
                ]
            ]
        ]; 
        return $this->post($this->endpoint(),$data); 
    }

    public function post($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)))
        ); 

        $results = curl_exec($ch);

        return json_decode($results, true);
    }


    public function get($url, $data, $auth)
    {
        $params = '';
        foreach($data as $key=>$value)
            $params .= $key.'='.$value.'&';

        $params = trim($params, '&');

        $ch = curl_init($url."?".$params);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . Auth::user()->wasla_token,
        ));

        return json_decode(curl_exec($ch), true);
    }
}
