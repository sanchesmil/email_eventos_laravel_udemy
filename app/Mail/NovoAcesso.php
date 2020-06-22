<?php

namespace App\Mail;

use App\User;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovoAcesso extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;  // define o atritubo $user

    public function __construct(User $user)  // recebe um User no construtor
    {
        $this->user = $user;
    }

    /**
     * Define o Corpo da mensagem do email.
     *
     * @return $this
     */
    public function build()
    {
        // retorna uma página com os dados do user logado e um arquivo em anexo
        return $this->view('emails.novoacesso')->with([
            'nome' => $this->user->name,
            'email' => $this->user->email,
            'datahora' => now()->setTimezone('America/Sao_Paulo')->format('d-m-Y H:i:s'),
        ])->attach( base_path() . '/arquivos/curriculo_pedro.pdf'); 

        // attach() = método usado para anexar arquivos ao email
    }
}
