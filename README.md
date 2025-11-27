# 🍳 GastroMaster - Sistema de Gerenciamento de Receitas Gastronômicas

Sistema web desenvolvido em **HTML5**, **CSS3**, **JavaScript**, **PHP** e **MySQL** para cadastro, edição, listagem e exclusão de receitas gastronômicas, com sistema de autenticação e controle de acesso.

## 📋 Requisitos do Sistema

- PHP 7.4 ou superior
- MySQL 5.7 ou superior (ou MariaDB)
- Servidor web (Apache/Nginx)
- Extensões PHP necessárias:
  - PDO
  - PDO_MySQL
  - GD (para manipulação de imagens)
  - fileinfo (para validação de tipos de arquivo)

## 🚀 Instalação

### 1. Configurar o Banco de Dados

1. Abra o phpMyAdmin ou seu cliente MySQL preferido
2. Importe o arquivo `database/schema.sql` para criar o banco de dados e as tabelas necessárias
3. Ou execute o script SQL manualmente:
   ```sql
   mysql -u root -p < database/schema.sql
   ```

### 2. Configurar a Conexão

Edite o arquivo `config/database.php` e ajuste as credenciais do banco de dados:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gastromaster');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Configurar o Diretório de Uploads

Certifique-se de que o diretório `uploads/` existe e tem permissões de escrita:

```bash
mkdir uploads
chmod 755 uploads
```

### 4. Configurar a URL do Sistema

Edite o arquivo `config/config.php` e ajuste a URL base do sistema:

```php
define('SITE_URL', 'http://localhost/Atividade_Final');
```

## 📁 Estrutura de Pastas

```
Atividade_Final/
├── assets/
│   ├── css/
│   │   └── style.css          # Estilos principais
│   └── js/
│       ├── validation.js      # Validações JavaScript
│       └── main.js            # Scripts principais
├── auth/
│   ├── login.php              # Página de login
│   ├── register.php           # Página de registro
│   └── logout.php             # Logout
├── config/
│   ├── config.php             # Configurações gerais
│   └── database.php           # Conexão com banco
├── database/
│   └── schema.sql             # Script SQL do banco
├── includes/
│   ├── header.php             # Cabeçalho do sistema
│   └── footer.php             # Rodapé do sistema
├── receitas/
│   ├── cadastrar.php          # Cadastro de receitas
│   ├── listar.php             # Listagem de receitas
│   ├── ver.php                # Visualização de receita
│   ├── editar.php             # Edição de receitas
│   └── excluir.php            # Exclusão de receitas
├── uploads/                   # Diretório de imagens
├── .htaccess                  # Configurações Apache
├── index.php                  # Página principal (Dashboard)
└── README.md                  # Este arquivo
```

## 🔐 Funcionalidades

### Autenticação
- ✅ Cadastro de usuários
- ✅ Login/Logout
- ✅ Proteção de áreas restritas
- ✅ Controle de sessão

### Gerenciamento de Receitas
- ✅ Cadastro de receitas
- ✅ Listagem de receitas
- ✅ Visualização detalhada
- ✅ Edição de receitas
- ✅ Exclusão de receitas
- ✅ Upload de imagens

### Campos da Receita
- Nome da receita
- Categoria (Sobremesa, Massa, Carne, Peixe, Ave, Vegetariano, Salada, Sopa, Bebida, Outro)
- Ingredientes
- Modo de preparo
- Tempo de preparo
- Nível de dificuldade (Fácil, Médio, Difícil)
- Imagem ilustrativa

## 🎨 Características

- **Design Responsivo**: Interface adaptável para desktop, tablet e mobile
- **Validações**: Validação tanto no front-end (JavaScript) quanto no back-end (PHP)
- **Segurança**: Uso de Prepared Statements (PDO) para prevenir SQL Injection
- **Upload Seguro**: Validação de tipo e tamanho de arquivos
- **Interface Intuitiva**: Design moderno e fácil de usar
- **Código Limpo**: Código organizado, comentado e seguindo boas práticas

## 🔒 Segurança

- Proteção contra SQL Injection (Prepared Statements)
- Validação de tipos de arquivo
- Controle de tamanho de arquivo (máx. 5MB)
- Proteção de sessão
- Sanitização de dados de entrada
- Proteção de arquivos sensíveis via .htaccess

## 📱 Responsividade

O sistema é totalmente responsivo e se adapta a diferentes tamanhos de tela:
- **Desktop**: Layout completo com grid de receitas
- **Tablet**: Layout adaptado com 2 colunas
- **Mobile**: Layout de coluna única com menu hambúrguer

## 🛠️ Tecnologias Utilizadas

- **Front-end**: HTML5, CSS3, JavaScript (Vanilla)
- **Back-end**: PHP 7.4+
- **Banco de Dados**: MySQL 5.7+
- **Servidor Web**: Apache (com mod_rewrite)

## 📝 Notas

- Este é um projeto acadêmico desenvolvido com tecnologias puras (sem frameworks)
- Todas as funcionalidades foram implementadas do zero
- O código está comentado para facilitar o entendimento
- O sistema foi desenvolvido seguindo boas práticas de desenvolvimento web

## 👨‍💻 Desenvolvedor

Desenvolvido como projeto final de faculdade utilizando tecnologias web puras.

## 📄 Licença

Este projeto foi desenvolvido para fins acadêmicos.

---

**GastroMaster** - Gerencie suas receitas gastronômicas de forma simples e prática! 🍳

