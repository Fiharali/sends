<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
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
        $message = 'welcome ';

        $client = new Client($sid, $token);

        try {
            $users = User::whereSends(0)->get();
            // dd($users);

            if ($users->count() > 0) {
                foreach ($users as $user) {

                    if ($user->phone) {
                        $recipientNumber = $user->phone;
                        $client->messages->create("whatsapp:+212$recipientNumber", ['from' => $whatsappNumber, 'body' => $message,]);
                        $user->sends = 1;
                        $user->save();
                    }
                }

                return redirect()->back()->with(['message', 'message sent with success ']);
            } else {
                //dd(2);
                return redirect()->back()->with('message', 'No users available to send the message to.');
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function send(Request $request)
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $whatsappNumber = env('TWILIO_WHATSAPP_NUMBER');
        $recipientNumber = '600873260';
        $messageBody = $request->message;
       // dd($messageBody);
        $client = new Client($sid, $token);

        try {
            // Vérifiez si le corps du message est vide
            if (empty($messageBody)) {
                throw new Exception("Message body cannot be empty.");
            }
    
            // Créez le message avec Twilio
            $client->messages->create("whatsapp:+212$recipientNumber", ['from' => $whatsappNumber, 'body' => $messageBody]);
    
            return redirect()->back()->with('message', 'Message sent successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

}