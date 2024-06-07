## README

### Projeto: Sistema de Cadastro de Usuários

Este é um projeto de exemplo que demonstra como criar um sistema de cadastro de usuários utilizando PHP e MySQL. Ele permite adicionar, editar e excluir usuários, bem como listar todos os usuários cadastrados.

### Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache, Nginx, etc.)

### Instalação

#### Windows

1. **Instale o XAMPP:**

   - Baixe o XAMPP em [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).
   - Instale o XAMPP seguindo as instruções do instalador.

2. **Configuração do MySQL:**

   - Abra o XAMPP Control Panel.
   - Inicie os módulos Apache e MySQL.
   - Clique em "Admin" ao lado de MySQL para abrir o phpMyAdmin.

3. **Criação do Banco de Dados:**

   - No phpMyAdmin, crie um banco de dados chamado `armalite_db`.

4. **Configuração do Projeto:**

   - Clone ou baixe este repositório no diretório `htdocs` do XAMPP (geralmente `C:\xampp\htdocs`).

5. **Configuração do Banco de Dados no Projeto:**

   - Abra o arquivo `index.php`.
   - Altere as configurações de conexão do banco de dados se necessário:
     ```php
     $conexao = new PDO("mysql:host=127.0.0.1:3306;dbname=armalite_db", "root", "");
     ```

6. **Importação da Estrutura do Banco de Dados:**

   - No phpMyAdmin, importe o arquivo SQL fornecido (`armalite_db.sql`) para criar a tabela `users`.

7. **Execução do Projeto:**

   - No navegador, acesse `http://localhost/nome_do_projeto`.

#### macOS

1. **Instale o MAMP:**

   - Baixe o MAMP em [https://www.mamp.info/en/](https://www.mamp.info/en/).
   - Instale o MAMP seguindo as instruções do instalador.

2. **Configuração do MySQL:**

   - Abra o MAMP.
   - Inicie os servidores Apache e MySQL.
   - Clique em "Open WebStart page" e depois em "phpMyAdmin".

3. **Criação do Banco de Dados:**

   - No phpMyAdmin, crie um banco de dados chamado `armalite_db`.

4. **Configuração do Projeto:**

   - Clone ou baixe este repositório no diretório `htdocs` do MAMP (geralmente `/Applications/MAMP/htdocs`).

5. **Configuração do Banco de Dados no Projeto:**

   - Abra o arquivo `index.php`.
   - Altere as configurações de conexão do banco de dados se necessário:
     ```php
     $conexao = new PDO("mysql:host=127.0.0.1:8889;dbname=armalite_db", "root", "root");
     ```

6. **Importação da Estrutura do Banco de Dados:**

   - No phpMyAdmin, importe o arquivo SQL fornecido (`armalite_db.sql`) para criar a tabela `users`.

7. **Execução do Projeto:**

   - No navegador, acesse `http://localhost:8888/nome_do_projeto`.

### Estrutura do Banco de Dados

```sql
CREATE TABLE armalite_db.users (
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(100),
    email VARCHAR(100),
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME
);
```

### Arquivos do Projeto

- `index.php`: Arquivo principal que contém a lógica para adicionar, editar, listar e excluir usuários.
- `armalite_db.sql`: Arquivo SQL para criação da tabela `users`.

### Uso

- Para adicionar um novo usuário, preencha o formulário e clique em "Enviar".
- Para editar um usuário existente, clique em "Editar" na lista de usuários, modifique os dados e clique em "Atualizar".
- Para excluir um usuário, clique em "Excluir" na lista de usuários.

### Observações

- Certifique-se de que os módulos Apache e MySQL estejam em execução.
- Ajuste as configurações de conexão do banco de dados conforme necessário para o seu ambiente.

### Suporte

Para quaisquer dúvidas ou problemas, sinta-se à vontade para abrir uma issue no repositório ou entrar em contato.