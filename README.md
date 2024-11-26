# Sistema de Gerenciamento de Eventos 

Uma demonstração deste projeto está disponível em <a href="https://infozilla.com.br/eventos" target="_blank">https://infozilla.com.br/eventos</a>. 

Este projeto é um sistema de gerenciamento de eventos que consiste em uma API RESTful backend desenvolvida em PHP e um frontend simples em HTML/JavaScript.

## Tecnologias Utilizadas 
### Backend: 
<ul>
    <li>PHP 8.3</li>
    <li>MySQL 8.0</li>
    <li>Padrão MVC (Model-View-Controller)</li>
    <li>PDO para conexão com o banco de dados</li>
    <li>Arquitetura RESTful</li>
</ul>

### Frontend: 
<ul>
    <li>HTML5</li>
    <li>JavaScript (Vanilla)</li>
    <li>FullCalendar 5.10.2</li>
</ul>

### Ambiente de Desenvolvimento: 
<ul>
    <li>Docker e Docker Compose</li>
    <li>Nginx como servidor web</li>
</ul>

### Estrutura do Projeto 
<ul>
    <li><b>backend/</b>: Contém todo o código PHP da API</li>
    <li><b>frontend/</b>: Contém o arquivo HTML e JavaScript do frontend</li>
    <li><b>docker/</b>: Contém as configurações do Docker</li>
</ul> 

## API Endpoints 
A API oferece os seguintes endpoints: 

<ul>
    <li>GET /events: Lista todos os eventos</li>
    <li>GET /events/{id}: Retorna um evento específico</li>
    <li>POST /events: Cria um novo evento</li>
    <li>PUT /events/{id}: Atualiza um evento existente</li>
    <li>DELETE /events/{id}: Exclui um evento (exclusão lógica)</li>
</ul> 

### Arquivo Postman 

Na raiz do projeto, há o arquivo Events-API-Collection-Postman.json. Este arquivo pode ser importado no Postman para testar todos os endpoints da API. 

## Como Rodar o Projeto 

### Usando Docker 

Certifique-se de ter o Docker e o Docker Compose instalados em seu computador. 

Na raiz do projeto, execute: 

``docker-compose -f docker/docker-compose.yml up -d`` 

Acesse o frontend em http://localhost:3000 

A API estará disponível em http://localhost:8000 

### Sem Docker 

#### Backend:

Certifique-se de ter PHP 8.3 e MySQL 8.0 instalados em seu computador. 

Configure seu servidor web (Apache ou Nginx) para apontar para o diretório backend/public. 

Importe o arquivo SQL em backend/database/01-create-table-events.sql para seu MySQL. 

Copie o arquivo .env.example para .env e configure as variáveis de ambiente, especialmente as relacionadas ao banco de dados. 

No diretório backend, execute: 

``php -S localhost:8000 -t public`` 

#### Frontend: 

No diretório frontend, você pode simplesmente abrir o arquivo index.html em seu navegador ou usar Apache, Nginx, ou um servidor web simples como o servidor embutido do PHP: 

``php -S localhost:3000`` 

Acesse o frontend em http://localhost:3000 
