<?php
namespace App\Http\Controllers;

use Salman\Mqtt\MqttClass\Mqtt;
use Illuminate\Http\Request;

class MqttController extends Controller{

    // public $token = "";

    // public function SendMsgViaMqtt(Request $request)
    // {
    //         $mqtt = new Mqtt();
    //         //$client_id = Auth::user()->id;/
    //         $topic = $request->topic;
    //         $token = $request->token;
    //         $message = $request->message;
    //         $output = $mqtt->ConnectAndPublish("test", $message, "");

    //         if ($output === true)
    //         {
             
    //             if($token == "none" || !$token){
    //                 return "End";
    //             }else{
    //                 $this->SubscribetoTopic($token);                    
    //             } 
    //         }else{
    //             return "Failed";
    //         }
    // }

    
    // public function SubscribetoTopic($token)
    // {
        
    //     $topic = 'test';
    //     $this->token = $token;
    //     $message = [];
    //     $mqtt = new Mqtt();
    //     $client_id = "";
    //     $mqtt->ConnectAndSubscribe($topic, function($topic, $msg){
    //         if($msg == "end"){
    //             $message = [
    //                 'title' => '魚が釣れました',
    //                 'body' => '釣竿を確認してください',
    //                 'click_action' => 'http://www.naver.com'
    //             ];
    //         }else if($msg == "no"){
    //             $message = [
    //                 'title' => '測定できません',
    //                 'body' => '波が強すぎると測れません',
    //                 'click_action' => 'http://www.naver.com'
    //             ];
    //         }else{
    //             return "end";
    //         }
    //         $this->sendCrul($this->token, $message);
    //     }, "");
        

        
    // }

    // public function sendCrul($token, $message){
        
    //     define('SERVER_API_KEY', 'AAAA3Jh2H2Q:APA91bH73OKOmpKBj30oCapsbFNqYW6rOw_X9OrHu2H9fusE9Px6Ei1-MOfGILbi5s1CfR5InRCespJlb42jh4tqwvZ40Ufow7PXCBvgzpOqQ07XO9X20mQ-YfmTnFhqS4S9wCpmNP1P');
    //     $tokens = $token;
    //     $header = [
    //         'Authorization: Key=' . SERVER_API_KEY,
    //         'Content-Type: Application/json'
    //     ];

    //     // $msg = [
    //     //     'title' => '魚が釣れました。',
    //     //     'body' => '釣竿を確認してください。',
    //     //     'click_action' => 'http://www.naver.com'
    //     // ];

    //     $payload = [
    //         'to' => $tokens,
    //         'notification' => $message
    //     ];

    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => json_encode( $payload ),
    //         CURLOPT_HTTPHEADER => $header
    //     ));

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);
    //     curl_close($curl);
    //     if($err){
    //         echo "cURL Error #:". $err;
    //     }else{
    //         return $response;
    //     }
    //     return "ok";
    // }
}