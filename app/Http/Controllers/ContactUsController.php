<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Rules\ReCaptchaV3;
// use recaptchav3;
use Validator;

class ContactUsController extends Controller
{
    //
    public function index(){
        $defaultLocale = config('app.locale');
        return view('pages.contact');
    }

    public function send(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:500'],
            'email' => ['required', 'email:rfc'],
            'subject' => ['required', 'string', 'max:150'],
            // 'g-recaptcha-response' => ['required', new ReCaptchaV3('submitContact')]
            'g-recaptcha-response' => 'required|recaptchav3:contact,0.5'
        ]);

        // $to = $request->email ;

        Mail::send('pages.email-contact', array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'subject' => $request->get('subject'),
            'user_query' => $request->get('message'),
        ), function($message) use ($request){
            $message->replyTo($request->get('email'), $request->get('name'));
            $message->to('winmaxcomp@gmail.com', 'Admin')->subject($request->get('subject'));
        });

        // RecaptCha V3 and other rules have passed, safe to continue

        // Here you can add code to actually send the email message

        // return view('pages.contact');
        return response('OK');
        
    }
}
