# rep_php
PHP basics and projects
O Login inicial é feito no index.php.
Em que o admin tem acesso  Login: Gabriel Senha: 12345 (está no DUMP)


A pessoa é direcionada para a aba Inserir médico
Aba Inserir médico:
- Ao submeter o form insere no banco de dados
-Segue as validações que foram pedidas no case (Telefone, CPF, CRM e Email)
-Segue as análises se foram preenchidas as entradas obrigatórias (como nome)
-Leitura do Webservice (do unasus)
-Quando as especialidades coloquei em um json (arrays.json) e puxei para a aba

Aba Listar médicos:
- Lista todos os médicos
- Permite alterar determinado médico (com um clique na opção alterar)
-Permite excluir determinado médico (com um clique na opção excluir)

Aba Buscar médicos:
- Permite buscar um médico pelo nome ou CRM
- Permite alterar e excluir esse médico

OBS:

Segue o DUMP do banco de dados com o nome gabrielaugusto.sql
Foram usadas as configurações passados pelo case para o banco de dados (usuário -> root, senha -> 123456 )
Foram feitos alguns incrementos para apenas o admin logado poder acessar as telas do sistema.
E foi utilizado o XAMPP para testes usando para o banco de dados o PHPMyAdmin
Segue em anexo um print da tela após login com o nome "exemploTela.jpg".
