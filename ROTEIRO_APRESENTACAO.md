# 🎯 Roteiro de Apresentação - GastroMaster

## 📌 1. INTRODUÇÃO DO PROJETO

### O que é o GastroMaster?
"Sistema web para gerenciamento de receitas gastronômicas desenvolvido em PHP puro (sem frameworks), HTML5, CSS3, JavaScript e MySQL. Permite aos usuários cadastrar, visualizar, editar e excluir suas receitas pessoais, com sistema completo de autenticação e controle de acesso."

### Objetivos do Projeto
- Sistema completo de CRUD (Create, Read, Update, Delete)
- Autenticação e controle de sessão
- Upload seguro de imagens
- Interface responsiva
- Código organizado e bem estruturado

---

## 🏗️ 2. ARQUITETURA E ESTRUTURA DO PROJETO

### Por que escolhi essa estrutura de pastas?

**Organização por responsabilidade** - Cada pasta tem um propósito claro:

```
Atividade_Final/
├── assets/          → Recursos estáticos (CSS, JS)
├── auth/            → Autenticação (login, registro, logout)
├── config/          → Configurações do sistema
├── database/        → Camada de acesso a dados (Repository Pattern)
├── includes/        → Componentes reutilizáveis (header, footer)
├── receitas/        → Funcionalidades de receitas (CRUD)
├── services/        → Serviços auxiliares (ImageUploader)
└── uploads/         → Arquivos enviados pelos usuários
```

**Vantagens dessa organização:**
- ✅ Fácil manutenção - encontro rapidamente qualquer funcionalidade
- ✅ Escalabilidade - fácil adicionar novos módulos
- ✅ Separação de responsabilidades - cada pasta tem uma função específica
- ✅ Segurança - uploads separados de código executável

---

## 📁 3. ONDE FICA CADA COISA NO CÓDIGO

### 3.1 Configurações e Conexão

**Pergunta esperada:** "Onde está a configuração do banco de dados?"

**Resposta:** "Está separada em dois arquivos no diretório `config/`:
- `config/config.php` → Configurações gerais (URLs, constantes, funções de sessão)
- `config/database.php` → Conexão com banco de dados usando PDO"

**O que mostrar:**
- `config/config.php` → Define constantes (SITE_URL, SITE_NAME, UPLOAD_DIR)
- `config/config.php` → Funções de autenticação (isLoggedIn, requireLogin, getCurrentUser)
- `config/database.php` → Função getConnection() usando PDO com opções de segurança

**Por que PDO ao invés de mysqli?**
- ✅ Prepared Statements nativos (proteção contra SQL Injection)
- ✅ Melhor tratamento de erros (PDOException)
- ✅ Suporte a múltiplos bancos de dados
- ✅ Código mais limpo e orientado a objetos

---

### 3.2 Sistema de Autenticação

**Pergunta esperada:** "Como funciona o login?"

**Resposta:** "O sistema de autenticação está no diretório `auth/`:
- `auth/login.php` → Página e lógica de login
- `auth/register.php` → Página e lógica de cadastro
- `auth/logout.php` → Encerra a sessão"

**O que mostrar:**
- `auth/login.php` → Valida email/senha, verifica com password_verify()
- `auth/login.php` → Cria sessão com user_id, user_nome, user_email
- Função `requireLogin()` em `config/config.php` → Protege páginas restritas

**Por que password_hash() e password_verify()?**
- ✅ Algoritmo bcrypt (seguro e recomendado pelo PHP)
- ✅ Salt automático (cada senha tem um salt único)
- ✅ Não preciso gerenciar hash manualmente
- ✅ Padrão da indústria

---

### 3.3 Camada de Acesso a Dados (Repository Pattern)

**Pergunta esperada:** "Onde estão as consultas ao banco?"

**Resposta:** "Usei o padrão Repository para centralizar todas as operações de banco em uma classe:
- `database/ReceitaRepository.php` → Todas as operações CRUD de receitas"

**O que mostrar:**
- Classe ReceitaRepository com métodos:
  - `findAllByUser($usuarioId)` → Lista receitas do usuário
  - `findByIdAndUser($id, $usuarioId)` → Busca receita específica
  - `create($dados)` → Cria nova receita
  - `update($id, $usuarioId, $dados)` → Atualiza receita
  - `delete($id, $usuarioId)` → Exclui receita

**Por que escolhi o Repository Pattern?**
- ✅ Separação de responsabilidades (lógica de negócio vs. acesso a dados)
- ✅ Reutilização de código (mesmos métodos em várias páginas)
- ✅ Fácil manutenção (mudanças no banco em um só lugar)
- ✅ Testabilidade (pode criar mocks para testes)
- ✅ Segurança centralizada (sempre valida usuario_id)

**Importante:** Todas as consultas verificam `usuario_id` - usuário só acessa suas próprias receitas!

---

### 3.4 Funcionalidades de Receitas (CRUD)

**Pergunta esperada:** "Onde está cada operação CRUD?"

**Resposta:** "Todas estão no diretório `receitas/`:

- **Create (Cadastrar):** `receitas/cadastrar.php`
  - Formulário HTML + processamento POST
  - Validação de campos obrigatórios
  - Upload de imagem (opcional)
  - Usa ReceitaRepository::create()

- **Read (Listar/Ver):**
  - `receitas/listar.php` → Lista todas as receitas do usuário
  - `receitas/ver.php` → Visualização detalhada de uma receita
  - Usa ReceitaRepository::findAllByUser() e findByIdAndUser()

- **Update (Editar):** `receitas/editar.php`
  - Carrega dados existentes no formulário
  - Permite alterar imagem (substitui ou mantém atual)
  - Usa ReceitaRepository::update()

- **Delete (Excluir):** `receitas/excluir.php`
  - Remove receita e imagem associada do servidor
  - Usa ReceitaRepository::delete()"

**Fluxo de edição:**
1. GET → Carrega receita do banco e exibe no formulário
2. POST → Valida dados, processa upload (se houver), atualiza no banco

---

### 3.5 Upload de Imagens

**Pergunta esperada:** "Como funciona o upload de imagens?"

**Resposta:** "Criei uma classe dedicada em `services/ImageUploader.php` para gerenciar uploads de forma segura."

**O que mostrar:**
- Classe ImageUploader com método upload()
- Validações implementadas:
  - ✅ Tamanho máximo (5MB) - definido em MAX_FILE_SIZE
  - ✅ Tipo de arquivo (MIME type real, não apenas extensão)
  - ✅ Nome único (uniqid()) para evitar conflitos
  - ✅ Remove imagem antiga ao atualizar

**Por que uma classe separada?**
- ✅ Reutilização (cadastrar.php e editar.php usam o mesmo código)
- ✅ Facilita manutenção (regras de upload em um só lugar)
- ✅ Facilita testes
- ✅ Código mais limpo e organizado

**Segurança no upload:**
- Valida MIME type real com `finfo()` (não apenas extensão do arquivo)
- Move arquivo para pasta segura (`uploads/`)
- Gera nome único para evitar sobrescrita

---

### 3.6 Interface e Design

**Pergunta esperada:** "Onde estão os estilos e scripts?"

**Resposta:** "Recursos estáticos em `assets/`:
- `assets/css/style.css` → Todos os estilos do sistema
- `assets/js/validation.js` → Validações JavaScript no front-end
- `assets/js/main.js` → Scripts gerais (menu mobile, etc.)"

**Componentes reutilizáveis:**
- `includes/header.php` → Cabeçalho com navegação (usado em todas as páginas)
- `includes/footer.php` → Rodapé (usado em todas as páginas)

**Por que separar includes?**
- ✅ DRY (Don't Repeat Yourself) - não repito código
- ✅ Mudanças em um lugar refletem em todas as páginas
- ✅ Manutenção mais fácil

---

### 3.7 Banco de Dados

**Pergunta esperada:** "Como está estruturado o banco?"

**Resposta:** "Script SQL em `database/schema.sql` com duas tabelas principais:"

**Tabela `usuarios`:**
- id, nome, email (único), senha (hash), data_cadastro

**Tabela `receitas`:**
- id, usuario_id (FK), nome, categoria, ingredientes (TEXT), modo_preparo (TEXT)
- tempo_preparo, nivel_dificuldade, imagem (caminho), data_cadastro, data_atualizacao

**Recursos:**
- ✅ Foreign Key com ON DELETE CASCADE (remove receitas ao excluir usuário)
- ✅ Índices em usuario_id e categoria (melhora performance)
- ✅ Charset utf8mb4 (suporta emojis e caracteres especiais)
- ✅ Campos de data automáticos (TIMESTAMP)

---

## 🔒 4. SEGURANÇA

**Pergunta esperada:** "Quais medidas de segurança você implementou?"

**Resposta detalhada:**

### 4.1 SQL Injection
- ✅ **Prepared Statements** em todas as consultas (PDO)
- ✅ Exemplo: `$stmt->prepare("SELECT * FROM receitas WHERE id = ? AND usuario_id = ?")`
- ✅ Parâmetros passados via `execute([$id, $usuarioId])`
- ✅ NUNCA concatenação de strings em SQL

### 4.2 XSS (Cross-Site Scripting)
- ✅ Função `sanitize()` em `config/config.php`
- ✅ `htmlspecialchars()` em todos os outputs HTML
- ✅ `ENT_QUOTES` para proteger aspas simples e duplas

### 4.3 Autenticação
- ✅ Senhas hasheadas com `password_hash()` (bcrypt)
- ✅ Verificação com `password_verify()`
- ✅ Controle de sessão
- ✅ Função `requireLogin()` protege páginas restritas

### 4.4 Autorização
- ✅ Todas as operações verificam `usuario_id`
- ✅ Usuário só acessa suas próprias receitas
- ✅ Verificação dupla: no Repository e nas páginas

### 4.5 Upload de Arquivos
- ✅ Validação de tipo real (MIME type)
- ✅ Limite de tamanho (5MB)
- ✅ Nome único (evita sobrescrita)
- ✅ Armazenamento em pasta separada

### 4.6 Sessões
- ✅ Verificação de sessão antes de acesso
- ✅ Redirecionamento automático se não logado
- ✅ Logout seguro (destrói sessão)

---

## 💡 5. ESCOLHAS TÉCNICAS E JUSTIFICATIVAS

### 5.1 Por que PHP sem frameworks?
- ✅ Projeto acadêmico - demonstra conhecimento das bases
- ✅ Controle total sobre o código
- ✅ Sem dependências externas
- ✅ Performance melhor (sem overhead de framework)

### 5.2 Por que PDO ao invés de mysqli?
- ✅ API mais moderna e orientada a objetos
- ✅ Prepared Statements mais intuitivos
- ✅ Melhor tratamento de erros (exceções)
- ✅ Suporte a múltiplos bancos

### 5.3 Por que Repository Pattern?
- ✅ Organização do código
- ✅ Facilita manutenção
- ✅ Reutilização de código
- ✅ Testabilidade

### 5.4 Por que separar includes (header/footer)?
- ✅ DRY (Don't Repeat Yourself)
- ✅ Manutenção centralizada
- ✅ Consistência visual

### 5.5 Por que classe ImageUploader separada?
- ✅ Reutilização (cadastro e edição)
- ✅ Código organizado
- ✅ Facilita testes e manutenção

---

## 📱 6. RESPONSIVIDADE

**Pergunta esperada:** "O sistema funciona em mobile?"

**Resposta:** "Sim! Implementei design responsivo:
- Media queries no CSS
- Menu hambúrguer para mobile (JavaScript)
- Grid adaptável (3 colunas → 2 → 1)
- Formulários otimizados para mobile"

---

## 🎯 7. DEMONSTRAÇÃO PRÁTICA (ORDEM SUGERIDA)

1. **Abrir o sistema** → Mostrar tela de login
   - Explicar: "Tela protegida, precisa autenticação"

2. **Fazer login** → Demonstrar autenticação
   - Explicar: "Validação com password_verify(), criação de sessão"

3. **Dashboard** → Mostrar página inicial
   - Explicar: "Exibe estatísticas e últimas receitas"

4. **Cadastrar receita** → Mostrar formulário completo
   - Explicar: "Validações front-end e back-end, upload de imagem"

5. **Listar receitas** → Mostrar lista
   - Explicar: "Busca apenas receitas do usuário logado"

6. **Editar receita** → Mostrar edição
   - Explicar: "Mantém imagem atual ou permite substituir"

7. **Visualizar receita** → Mostrar detalhes
   - Explicar: "Exibe todos os dados formatados"

8. **Excluir receita** → Demonstrar exclusão
   - Explicar: "Remove do banco e imagem do servidor"

---

## ❓ 8. PERGUNTAS PROVÁVEIS DO PROFESSOR E RESPOSTAS

### P1: "Por que não usou framework (Laravel, CodeIgniter)?"
**Resposta:** "Escolhi PHP puro para demonstrar conhecimento das bases. Em projetos maiores, um framework seria mais adequado, mas para este projeto, o PHP nativo oferece controle total e código mais leve."

### P2: "Onde está a validação?"
**Resposta:** "Implementei validação em duas camadas:
- **Front-end:** JavaScript em `assets/js/validation.js` (melhor UX)
- **Back-end:** PHP nas páginas de cadastro/editar (segurança obrigatória)"

### P3: "Como você previne SQL Injection?"
**Resposta:** "Uso Prepared Statements do PDO em todas as consultas. Exemplo no ReceitaRepository - nunca concateno strings em SQL, sempre uso placeholders (?) e passo parâmetros via execute()."

### P4: "O que acontece se o banco cair?"
**Resposta:** "Implementei tratamento de erros com try/catch e verifico se getConnection() retorna null. O sistema registra erros em log e exibe mensagens amigáveis ao usuário."

### P5: "Como você garante que o usuário só vê suas receitas?"
**Resposta:** "Duas camadas de proteção:
1. Função requireLogin() garante que está autenticado
2. Todas as queries verificam usuario_id - mesmo que alguém tente acessar ID de outra receita, o WHERE usuario_id = ? impede acesso"

### P6: "O que acontece se dois usuários cadastrarem ao mesmo tempo?"
**Resposta:** "O banco de dados MySQL gerencia concorrência automaticamente com transações. Os índices em usuario_id e id garantem consultas rápidas mesmo com muitos usuários."

### P7: "Por que separou Repository em uma classe?"
**Resposta:** "Para seguir o padrão de design Repository Pattern. Centraliza toda lógica de acesso a dados, facilita manutenção, permite reutilização e melhora testabilidade."

### P8: "Como você valida upload de imagens?"
**Resposta:** "Classe ImageUploader faz múltiplas validações:
- Tamanho máximo (5MB)
- Tipo real usando finfo() (não apenas extensão)
- Gera nome único para evitar conflitos
- Remove imagem antiga ao atualizar"

### P9: "E se alguém tentar enviar um arquivo malicioso?"
**Resposta:** "Valido o MIME type real do arquivo com finfo(), não apenas a extensão. Mesmo que alguém renomeie um .exe para .jpg, o sistema detecta o tipo real e rejeita."

### P10: "O sistema está pronto para produção?"
**Resposta:** "Para ambiente acadêmico, sim. Para produção, precisaria adicionar:
- HTTPS obrigatório
- Rate limiting
- Logs mais detalhados
- Backup automático
- Testes automatizados"

---

## 📝 9. PONTOS FORTES DO PROJETO

✅ **Código organizado** - Estrutura clara e lógica
✅ **Segurança** - Múltiplas camadas de proteção
✅ **Repository Pattern** - Boa prática de desenvolvimento
✅ **Separação de responsabilidades** - Cada arquivo tem função clara
✅ **Responsivo** - Funciona em todos os dispositivos
✅ **Código comentado** - Fácil manutenção
✅ **Validações duplas** - Front-end e back-end
✅ **Tratamento de erros** - Sistema robusto

---

## 🎓 10. CONCLUSÃO

"Sistema completo de gerenciamento de receitas desenvolvido com tecnologias puras, seguindo boas práticas de desenvolvimento web, com foco em segurança, organização e manutenibilidade. O código está preparado para evolução e novas funcionalidades."

---

## 💼 DICAS PARA A APRESENTAÇÃO

1. **Conheça o código** - Estude bem antes de apresentar
2. **Navegue pelos arquivos** - Mostre onde cada coisa está
3. **Demonstre segurança** - Mostre as validações e proteções
4. **Justifique escolhas** - Sempre explique o "porquê"
5. **Seja honesto** - Se não souber algo, admita e mostre como pesquisaria
6. **Mostre o banco** - Abra o phpMyAdmin se possível
7. **Teste ao vivo** - Cadastre uma receita durante a apresentação

---

**Boa apresentação! 🚀**

