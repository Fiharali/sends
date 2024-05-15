<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
class TestController extends Controller
{

    public function index()
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $whatsappNumber = env('TWILIO_WHATSAPP_NUMBER');
        $recipientNumber = '+212600873260';
        $message = 'hello 1';

        $client = new Client($sid, $token);

        try {
            $client->messages->create(
                "whatsapp:$recipientNumber",
                [
                    'from' => $whatsappNumber,
                    'body' => $message,
                ]
            );
            return response()->json(['success' => true, 'message' => 'Message sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public  function  send(){

        $whatsappSender = new LaravelWhatsappSender();

        $phone = '212612796274';

        $message = 'Hello, this is a test message!';

        $response = $whatsappSender->sendTextMessage($phone, $message);
    }

}
