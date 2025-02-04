<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Markdown;
 
class MeuEmail extends Mailable
 {
     public function __construct()
     {
         // Construtor do e-mail
     }
 
     public function build()
     {
         return $this->subject('Assunto do e-mail')
                     ->view('emails.meu_email')
                     ->with([
                         'nome' => 'João',
                         'mensagem' => 'Olá, tudo bem?',
                     ]);
     }
 }