<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        return view('admin.Dashboard' ,[
            'users' =>User::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function sendSelectedUsers(Request $request)
    {
        $userIds=$request->selected_users;
       // return response()->json(['data' => $request->message]);
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $whatsappNumber = env('TWILIO_WHATSAPP_NUMBER');
        $recipientNumber = '+212600873260';
        $message = $request->message ;
        $client = new Client($sid, $token);
        try {
            $users = User::whereIn('id', $userIds)->get();
           if ($users->count>0){
               foreach ($users as $user) {
                   if ($user->phone) {
                       $recipientNumber = $user->phone;
                       $client->messages->create("whatsapp:+212$recipientNumber", ['from' => $whatsappNumber, 'body' => $message,]);
                       $user->sends = $user->sends + 1;
                       $user->save();
                   }
               }
               return redirect()->back()->with(['message', 'message sent with success ']);

           }

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
        //return response()->json(['data' => $users]);

    }


}
