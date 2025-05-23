# Sistema de Controle de Chamados (PHP)

Sistema web para controle de chamados da, desenvolvido com PHP, MySQL e Bootstrap.

## Funcionalidades

- Cadastro de chamados
- Listagem com filtros e busca
- Visualização detalhada
- Edição e exclusão de chamados
- Interface responsiva e amigável

## Requisitos

- PHP 7.4+
- MySQL 5.7+
- Servidor web (Apache/Nginx)

## Instalação

1. Clone o repositório:

```bash
git clone [URL_DO_REPOSITÓRIO]
cd projeto-chamados
```

2. Configure seu servidor web para apontar para o diretório do projeto.

3. Importe o banco de dados:

```bash
mysql -u seu_usuario -p < database/schema.sql
```

4. Configure as credenciais do banco de dados no arquivo `config/database.php`:

```php
private $host = "localhost";
private $db_name = sistema_chamados";
private $username = "seu_usuario";
private $password = "sua_senha";
```

5. Acesse o sistema através do navegador:

```
http://localhost/projeto-chamados
```

## Estrutura do Projeto

```
projeto-chamados/
├── config/
│   └── database.php
├── controllers/
│   └── ChamadoController.php
├── models/
│   └── Chamado.php
├── views/
│   └── chamados/
│       ├── index.php
│       ├── create.php
│       └── edit.php
├── database/
│   └── schema.sql
└── index.php
```

## Tecnologias Utilizadas

- PHP 7.4+
- MySQL
- Bootstrap 5
- Font Awesome
- PDO para conexão com banco de dados

## Segurança

- Uso de PDO com prepared statements para prevenir SQL injection
- Escape de dados na saída para prevenir XSS
- Validação de dados nos formulários
- Proteção contra CSRF (a ser implementado)

## Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request
