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

        // Envia um email para quem logou.  Neste caso, passa o próprio nome do usuário
        // Poderia passar como argumento do 'to': user, user[], email
        Mail::to($event->user)->
              send(new NovoAcesso($event->user));  // NovoAcesso é a classe de email criada por mim 
    }
}
