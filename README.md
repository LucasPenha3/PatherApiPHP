# PathernApiPHP
Api Padrão em PHP para uso geral.

Desenvolvida em PHP 8 com MVC e Orientação e objeto
 - Gerenciador de pacotes via composer
 - Design Pahern - Singleton - Factory Method
 - Clean Code
 - DTO / Entidades
 - Reflection ORM - DAO
 - Libs de conexão com banco de dados, AWS S3, Correios, Files, etc.
 - Arquivos úteis para validações, serializações, formatações de objetos, logs, etc.
 
# Configuração inicial:
A Api não conta com autenticação, é preciso implementar. Será o próximo passo, token via JWT.<br><br>
O Endponit Health é a porta de entrada da API.<br>
Para inciar os trabalhos com banco de dados basta configurar o arquivo de configuração na pasta ``CONF/`` e fazer um ``GET`` simples para ``/Health/Conect``
