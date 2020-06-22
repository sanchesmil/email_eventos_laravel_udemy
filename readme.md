## Criação de Eventos e envio de E-mail

Neste projeto foram implementadas as funcionalidades de:
- Criação de Eventos (events) e Ouvintes (Listeners)
- Configuração, criação e envio de e-mail

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


