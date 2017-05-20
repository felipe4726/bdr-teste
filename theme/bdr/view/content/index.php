<div class="container">
    <h1>
        Olá <?php echo (\Bdr\Sistema::app()->webUser) ? \Bdr\Sistema::app()->webUser->display_name : 'Visitante'; ?>,
    </h1>
    <div class="row">
        <div class="col-md-6">
            <h3>Atividade 1</h3>
            <p>Escreva um programa que imprima números de 1 a 100. Mas, para múltiplos de 3 imprima
                “Fizz” em vez do número e para múltiplos de 5 imprima “Buzz”. Para números múltiplos
                de ambos (3 e 5), imprima “FizzBuzz”.</p>
            <button onclick="rodar1();" class="btn btn-primary">Rodar</button>
            <pre id="atividade1" class="prettyprint" style="max-height: 338px;overflow: auto;">
&lt;?php //Código Fonte na raíz: fizzbuzz.php
    fizzbuss();
    function fizzbuss($i = 1)
    {
        if ($i % 3 == 0)
            print "Fizz";
        if ($i % 5 == 0)
            print "Buzz";
        if ($i % 3 !== 0 && $i % 5 !== 0)
            print $i;
        print "&lt;br&gt;";
        if ($i < 100)
            fizzbuss(++$i);
    }
                </pre>
            <script>
                function rodar1() {
                    jQuery.ajax({
                        url: '/fizzbuzz.php',
                        dataType: 'html',
                        success: function (response) {
                            $("#atividade1").html(response);
                        }
                    });
                }
            </script>
        </div>
        <div class="col-md-6">
            <h3>Atividade 2</h3>
            <p>Refatore o código abaixo, fazendo as alterações que julgar necessário.
            <pre class="prettyprint">
&lt;?

 if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
   header("Location: http://www.google.com");
   exit();
 } elseif (isset($_COOKIE['Loggedin']) && $_COOKIE['Loggedin'] == true) {
   header("Location: http://www.google.com");
   exit();
 }
                        </pre>
            </p>
            <p>Resposta:
            <pre class="prettyprint">
&lt;?php

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: http://www.google.com");
    exit();
} elseif (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == true) {
    header("Location: http://www.google.com");
    exit();
}
                        </pre>
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <h3>Atividade 3</h3>
        <p>Refatore o código abaixo, fazendo as alterações que julgar necessário.
        <pre class="prettyprint">
&lt;?php

 class MyUserClass
 {
     public function getUserList()
     {
         $dbconn = new DatabaseConnection('localhost','user','password');
         $results = $dbconn->query('select name from user');

         sort($results);

         return $results;
     }
 }

                    </pre>
        </p>

        <p>Resposta:
        <pre class="prettyprint">
&lt;?php

class User
{
    public function getList()
    {
        $dbconn = new DatabaseConnection('localhost','user','password');
        $results = $dbconn->query('select name from user');
        sort($results);
        return $results
    }
}
                    </pre>
        </p>
    </div>
    <div class="col-md-6">
        <h3>Atividade 4</h3>
        <img class="pull-right" src="<?php echo \Bdr\Config::APPURL . \Bdr\Sistema::app()->getTemplatePath(); ?>img/tarefa.png" />
        <p>Desenvolva uma API Rest para um sistema gerenciador de tarefas(inclusão/alteração/exclusão). As tarefas
            consistem em título e descrição, ordenadas por prioridade.</br>
            Desenvolver utilizando:</br>
            • Linguagem PHP (ou framework CakePHP);</br>
            • Banco de dados MySQL;</br>
            </br>
            Diferenciais:</br>
            • Criação de interface para visualização da lista de tarefas;</br>
            • Interface com drag and drop;</br>
            • Interface responsiva (desktop e mobile);
        </p>
        <pre>
    API:
      URL Inclusão e Alteração: /tarefa/jsonEdit
      Parametros: id, Tarefa[titulo], Tarefa[descricao], Tarefa[ordem], Tarefa[status] (caso o parametro id seja passado, é uma alteração, caso contrário, insert.
      URL Listagem: /tarefa/jsonList
      Parametros: Tarefa[titulo], Tarefa[descricao], Tarefa[id_usuario], Tarefa[id], Tarefa[ordem], Tarefa[status], Tarefa[created_at]
      URL Delete: /tarefa/jsonDelete
      Parametros: id

    A API para insert e Delete espera que o usuário esteja logado, mas pode ser passado as credenciais por parametro também. Parametros: ulogin e usenha
    ulogin=bdr&usenha=123456
    Exemplo de como usar a API:
      http://test.localhost/tarefa/jsonEdit/?Tarefa[titulo]=olá mundo&Tarefa[descricao]=nao precisa fazer nada
      http://test.localhost/tarefa/jsonList/
      http://test.localhost/tarefa/jsonList/?Tarefa[titulo]=olá
      http://test.localhost/tarefa/jsonDelete/?id=2&ulogin=bdr&usenha=123456
        </pre>
        <a class="btn btn-primary" href="<?php echo \Bdr\Vendor\Router::getRouter()->createUrl('tarefa', 'lista'); ?>">Gerenciador
            de Tarefa</a>
    </div>
</div><!-- /.container-fluid -->

<?php
?>