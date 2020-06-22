<?php

namespace App\Listeners;

use App\Events\HomeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HomeEventListener
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
     * O método Handle 'trata' o evento quando ele é gerado
     *
     * @param  HomeEvent  $event
     * @return void
     */
    public function handle(HomeEvent $event)  //Recebe o tipo de evento como argumento
    {
        info("Entrou na Home -Executou o listener HomeEventListener");
        info($event->text);

        // Obs.: Se habilitar o 'return false' vai quebrar a cadeia de ouvintes, 
        //       não executando os demais listeners vinculados ao evento
        
        //return false; 
    }
}
