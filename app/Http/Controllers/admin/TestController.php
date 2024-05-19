<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMessage;

class TestController extends Controller
{

    public function index(Request $request)
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $whatsappNumber = env('TWILIO_WHATSAPP_NUMBER');
        $recipientNumber = '+212600873260';
        $message = 'welcome  ';

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
        //$recipientNumber = '+212600873260';
        $message = $request->message;

        $client = new Client($sid, $token);

        try {
            $users = User::all();
            // dd($users);

            //  if ($users->count() > 0) {
            foreach ($users as $user) {

                if ($user->phone) {
                    $recipientNumber = $user->phone;
                    $client->messages->create("whatsapp:+212$recipientNumber", ['from' => $whatsappNumber, 'body' => $message,]);
                    $user->sends = $user->sends + 1;
                    $user->save();
                }
            }
            return redirect()->back()->with(['message', 'message sent with success ']);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }
    public function sendMail(Request $request)
    {
        $viewData = [
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        try {
            $users = User::all();

            foreach ($users as $user) {
                Mail::to($user->email)->send(new SendMessage($viewData));
            }

            return redirect()->back()->with('success', 'Message sent to all users successfully');
        } catch (Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


}