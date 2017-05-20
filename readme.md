Teste BDR
====
Isto é um teste

Código
-----
Sem contar os arquivos dentro da pasta /src/ext , Todo o código é de minha autoria, é um microframework.
Na raiz está o autoloader, e a index faz o bootstrap da aplicação. (fizzbuzz é para a solução da atividade 1).
O htaccess é essencial para funcionamento da aplicação.

dentro de src ficam os arquivos Config (configuração) e Sistema (Base da aplicação).
Migrate é para a instalação e atualização dos dados no banco de dados.
Dentro de Vendor estão os demais arquivos que compões o microframework.
Controller e Model são inerentes ao que está a ser solucionado.

A pasta Theme é ondem ficam os arquivos de visão da aplicação.

Como Rodar
-----
Extraia a aplicação em um servidor PHP 5.3+  
A aplicação precisa rodar em um subdominio   
 exemplo: http://teste.localhost

O arquivo de configuração da URL base está em /src/Config.php
A configuração de database, usuário e senha de banco de dados
também devem ser configurados no /src/Config.php

Estando isso pronto, ao rodar a url da aplicação o sistema instalará as tabelas na base de dados automáticamente.

Está sendo criado um usuario: bdr  senha: 123456

Na tela inicial é possivel ver a resposta das 3 primeiras questões.


Em relação a questão 4:

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