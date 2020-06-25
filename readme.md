## Criação de Eventos, E-mails e Filas (Queue + Redis)

Neste projeto foram implementadas as funcionalidades de:
- Criação de Eventos (events) e Ouvintes (Listeners)
- Configuração, criação e envio de e-mail
- Configuração de Filas (queues) usando o Redis

### Eventos

Os eventos do Laravel fornecem uma implementação simples do padrão 'observer',
permitindo que você cadastre e ouça vários eventos que ocorrem no seu aplicativo.

Um único evento pode ser mapeado para vários ouvintes independentes entre si.

Exemplos de eventos: Tentativa de Login, Acesso a determinada área da aplicação, etc

#### 1º passo: Registrar eventos e ouvintes 

- Os eventos e seus ouvintes devem ser retistrados/mapeados em 'app\http\Providers\EventServiceProvider.php'.
- O atributo 'listen' contém uma array que mapeia todos os eventos (chaves) e seus ouvintes (valores). 
  Você pode adicionar tantos eventos a essa matriz quanto seu aplicativo exigir.
  Você pode adicionar/mapear vários ouvintes para cada evento o quanto seu aplicativo exigir.

  Ex. Registrar/mapear o evento 'OrderShipped' junto ao ouvinte 'SendShipmentNotification'

  protected $listen = [
    'App\Events\OrderShipped' => [
        'App\Listeners\SendShipmentNotification',
    ],
  ];

#### 2º passo: Gerar Eventos e Ouvintes

Após executar o 1º passo (adicionar o evento e o ouvinte ao EventServiceProvider),
execute o comando abaixo para gerar os eventos e ouvintes:

- php artisan event:generate

Obs.: Ao executar este comando, serão criadas as pastas 'Events' e 'Listeners' 
na pasta raiz 'app\' com os eventos e ouvintes registrados no passo anterior.

Outra forma de criar Eventos e Listeners diretamente é através dos comandos abaixo:

- php artisan make:listener nome_listener

- php artisan make:event nome_evento

#### 3º Passo: Capturar o evento quando uma ação ocorrer

Exemplo 1: Acionamento de evento que registra em log o acesso à pagina Home.

- 1º - Foi criado o evento 'HomeEvent.php' que recebe uma mensagem quando é instanciado.
- 2º - Foi criado o ouvinte 'HomeEventListener.php' que captura o evento quando ele é 
gerado e armazena em log o texto contido no evento.
- 3º - O acionamento do evento ocorre no controlador 'HomeController.php', no método 'index.php',
antes de chamar a view 'home.blade.php'.

Exemplo 2: Acionamento de evento que registra em log o email do usuário que fez login

Obs.: Por padrão, quando usamos a estrutura de Auth do Laravel são criados vários eventos 
para cada momento da autenticação: Login.php, Registered.php, Logout.php, Failed.php, etc.
Esses eventos de Auth do Laravel são armazenados em 'vendor\laravel\framework\Auth\Events'.

- 1º - Foi criado o listener 'LoginListener.php' que captura o evento 'Login.php' do Laravel
e armazena em log o nome do usuário que logou.
- 2º - Foi necessário registrar o mapeamento do listener 'LoginListener.php' para o 
evento 'Login.php' em 'providers\EventServiceProvider.php'.
- 3º - Neste caso, o acionamento do evento ocorre automaticamente quando o usuário loga no sistema, sem a necessidade de instanciar o evento 'Login.php' em algum controlador.

### Envio de e-mail

#### Configuração do arquivo '.env' para envio de email na PR:

- MAIL_HOST=10.1.2.150    
- MAIL_PORT=25
- MAIL_ENCRYPTION=null
- MAIL_FROM_ADDRESS=uds@dominio.gov.br  // opcional
- MAIL_FROM_NAME="Laravel - Meu App"    // opcional

Obs.1: Quando a aplicação estiver em produção, as configurações devem ser feitas diretamente no arquivo 'config\mail.php'.
Obs.2: Essa configuração serve para enviar e-mails tanto para o dominínio interno da PR quanto
para domínios externos como gmail e yahoo.

#### Gerando classes de e-mail

No Laravel, cada tipo de email enviado pelo seu aplicativo é representado como uma classe. 
Essas classes são armazenadas no diretório 'app/Mail'. 
O diretório 'app/Mail' é gerado quando você cria sua primeira classe de envio de email usando o comando 'make:mail':

Ex.: Criação da classe de email 'NovoAcesso.php' que envia email ao usuário quando ele loga:

    php artisan make:mail NovoAcesso

Obs.: A classe 'NovoAcesso.php' recebe no construtor uma instância do User que logou.
  
#### Escrevendo os campos do e-mail

Toda a configuração de uma classe de e-mail é feita no método 'build()'.
Dentro desse método, você pode chamar vários métodos, como 'from', 'subject',
'view' e 'attach'. Eles servem para configurar os dados de apresentação e 
entrega do e-mail.

#### Configurando uma view de exibição do e-mail

No método build() da classe de email, você pode usar o método view() para especificar 
a view que deve ser usada para renderizar o conteúdo do email (é opcional). 
Cada tipo de email pode usar um modelo específico de página HTML para renderizar seu conteúdo.

Ex.: Foi criada a view 'novoacesso.blade.php' em 'resources/views/emails' que possui a estrutura
HTML do e-mail que será apresentado ao destinatário final após ele logar na aplicação.

#### Envio de email após login

No exemplo deste projeto, após o usuário logar na aplicação é enviado um email para ele 
informando tal ação.

- 1º Passo: Criação da classe de e-mail 'NovoAcesso.php' e da view 'novoacesso.blade.php' que renderizará o conteúdo do email.

- 2ª Passo: Implementação do envio do email em 'LoginListener.php'
 
* //Envia um email para quem logou passando o próprio usuário, todavia poderia passar como argumento do método 'to()': user, user[] ou email do usuário.
* //'Login' é o nome do evento gerado pelo Laravel
* //'NovoAcesso' é a classe de email criada especificamente para a ação de login

public function handle(Login $event) {
  
  Mail::to($event->user)->send(new NovoAcesso($event->user));  
  
}

### Redis (Trabalhando com fila de emails)

O Redis consiste em um banco de dados que armazena tuplas (chave e valor) em memória. 
Por valor entende-se um simples dado primitivo, como número ou string, mas que também 
pode ser um array complexo de objetos e outros arrays internos.

Essa arquitetura do redis permite que ele seja utilizado como uma camada de cache para 
aplicações que recebem requisições para retornar frequentemente alguns dados. 
Estes dados, em vez de serem buscados em arquivos ou remotamente, são prontamente 
retornados ao cliente requisitante em um tempo muito reduzido.

#### Instalação do Redis no windows

2 FORMAS: 

1ª - Baixar o instalador do Redis para windows:

  https://github.com/MicrosoftArchive/redis/releases

  Foi instalada a versão 3.2 do Redis na máquina.

  * Obs.: Após instalar o REDIS é necessário ativar o serviço na guia 'Serviços' do windows.

2ª - Habilitando o WSL (Subsistema Windows para Linux).
  
  1º Habilitar o WSL na máquina local em 'Recursos do Windows'
  
  2º Baixar e instalar o SO Ubuntu pela 'Microsoft Store'.

  3º Instalação do REDIS: Após instalar o Ubuntu foi instalado o REDIS.

  * Iniciar o serviço:  Após instalar o REDIS no Ubuntu usando o WSL é necessário iniciar o serviço:
  
        cmd> wsl (acessar no modo Unix do Ubuntu)

        cmd> redis-serve 

  * Parar o serviço:

        cdm> wsl (em outro terminal)

        cdm> redis-cli

        cdm> shutdown nosave

  * Obs.: As duas formas de instalação e uso do REDIS funcionaram perfeitamente.

#### Instalação do Pacote 'predis' no laravel

Antes de usar o Redis com o Laravel, você precisará instalar o pacote via Composer:

  composer require predis/predis

### Envio de E-mails com Filas (queues)

Um ponto que deve ser avaliado no momento de enviar e-mails é a questão do tempo,
o envio de e-mails é uma tarefa pesada, que tem um tempo de resposta razoavelmente grande, 
por esse motivo usar Queues (filas) é uma boa alternativa.

A ideia para uso de filas é armazenar 'eventos' que demoram certa quantidade de tempo para ser 
concretizada como, por exemplo, gerar um relatório, fazer upload de arquivo, consultar um servidor
remoto ou enviar emails para vários destinatários.

#### Filas (queues) de emails:

Podemos definir que o e-mail será enviado e processado pelo sistema de Queues (filas) do Laravel, 
basta no momento de fazer o envio trocar o método send() por queue(), veja:

public function handle(Login $event) { 
  Mail::to($event->user)->queue(new NovoAcesso($event->user));   
}

#### Postergando o envio de e-mails (minutos,horas e dias):

Caso queira por exemplo enviar e-mails só depois de X minutos/horas/dias pode fazer isso 
usando o método later() e passar o tempo after (depois) que é para disparar o e-mail, veja:

  $tempo = now()->addMinutes(10);
  Mail::to($to)->later($tempo, new SendMailUser());

Nesse exemplo só vai disparar o e-mail (processar a fila) após 10 minutos.

#### Ativando o serviço de Fila no laravel

A ideia é executar o serviço de filas em 'paralelo' à aplicação principal,
não impactando no seu desempenho.

Comando:  php artisan queue:work 

### Laravel Horizon

O Horizon fornece um belo painel e uma configuração orientada por código para suas filas Redis com Laravel. O Horizon permite monitorar facilmente as principais métricas do seu sistema de filas, como taxa de transferência, tempo de execução e falhas de tarefas.

Obs.: O Horizon não funciona no Windows. Ele utiliza a extensão pcntl que não está 
presente nas suas dlls e que não é possível instalar.