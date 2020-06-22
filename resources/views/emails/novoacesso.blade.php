<html>
    <body>
        <h4>Seja bem vindo(a) {{$nome}}</h4>
        <p>Você acabou de acessar o sistema usando seu e-mail {{$email}}.</p>
        <p>Data/Hora de acesso: {{$datahora}} </p>

        {{-- Mostra o logo do laravel no corpo do email --}}
        <div>  
            {{-- A variável $message é própria do blade.
                 $message->embed() vai 'embutir' dentro da src a imagem do logo do laravel  
                 pegando ela no caminho absoluto da pasta 'public' = public_path()  
            --}}
            <img width="10%" height="10%"
                 src="{{ $message->embed( public_path() . '/img/logo_laravel.png' ) }}" alt="">
        </div>
    </body>
</html>