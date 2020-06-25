<?php

namespace App\Listeners;

use App\Mail\NovoAcesso;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

// Listener que 'escuta' o evento 'Login' 
class LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)  // 'Login' é um evento chamado por 'Illuminate\Auth\Events\Login'
    {
        info('Logou!');
        info($event->user->email);  // Mostra o email de quem logou

        $tempo = now()->addMinutes(1);  // Define um momento futuro para o envio de email postergado

        // Envia um email para quem logou.  Neste caso, passa o próprio nome do usuário
        // Poderia passar como argumento do 'to': user, user[], email
        Mail::to($event->user)->
              //send(new NovoAcesso($event->user));  // send = envia o email imediatamente 
             
              //queue(new NovoAcesso($event->user));  // queue = cria uma fila de emails e os envia 

              later($tempo, new NovoAcesso($event->user)); // later = // posterga o envio de email por X minutos/horas/dias
        }
}
