<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class MailController extends Controller
{

    private $name;
    private $email;
    private $message;
    private $phone;
    private $subject;

    public function __construct($details = [])
    {
        $this->name = $details['name'];
        $this->email = $details['email'];
        $this->message = $details['message'];
        $this->subject = $details['subject'];
    }

    public function sendEmail()
    {
        $details = [
            'title'=> 'E-mail enviado pelo site',
            'name' => $this->name,
            'email'=> $this->email,
            'fone' => $this->phone,
            'message' => $this->message,
            'subject' => $this->subject,
        ];

        Mail::to( config('mail.from.address'))->send( new SendEmail($details));
    }
}