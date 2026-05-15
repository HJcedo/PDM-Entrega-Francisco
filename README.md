# Programe.C

Aplicativo mobile educacional de quiz para estudantes de TI, desenvolvido como projeto da disciplina de Programação para Dispositivos Móveis.

## Descrição

O Programe.C permite que estudantes pratiquem conteúdos de TI respondendo exercícios interativos. Após o login, o usuário escolhe uma matéria e responde uma série de questões, recebendo feedback imediato e uma nota ao final.

## Funcionalidades

- Cadastro e login de usuário com senha criptografada (bcrypt)
- Tela inicial com grade de matérias disponíveis
- Quiz com 3 tipos de exercícios: múltipla escolha, verdadeiro/falso e completar código
- Barra de progresso durante o quiz
- Tela de resultado com nota e número de acertos
- Tela de perfil com seleção de avatar
- API REST em PHP comunicando com banco PostgreSQL

## Tecnologias utilizadas

| Camada | Tecnologia |
|---|---|
| Frontend mobile | Flutter (Dart) + Material Design 3 |
| Backend | PHP puro (sem frameworks) |
| Banco de dados | PostgreSQL (Supabase) |
| Comunicação | HTTP REST com JSON |
| Servidor local | XAMPP |

## Estrutura do repositório

```
PDM-Entrega-Francisco/
│
├── app_flutter/
│   └── lib/
│       ├── main.dart
│       ├── models/       → Usuario, Materia, Exercicio, Tentativa
│       ├── screens/      → Login, Cadastro, Home, Exercicio, Resultado, Perfil
│       └── services/     → ApiService (comunicação com a API)
│
├── backend_php/
│   ├── config/
│   │   └── Banco.php     → classe de conexão PDO com PostgreSQL
│   └── endpoints/
│       ├── cadastro.php
│       ├── login.php
│       ├── perfil.php
│       ├── atualizar_usuario.php
│       ├── deletar_usuario.php
│       ├── materias.php
│       ├── exercicios.php
│       └── tentativa.php
│
├── banco_dados/
│   └── banco.sql         → CREATE TABLE e INSERTs iniciais
│
└── README.md
```

## Como testar localmente

### Backend (XAMPP)

1. Instale o [XAMPP](https://www.apachefriends.org/) e inicie o **Apache**
2. Copie a pasta `backend_php/` para dentro de `C:\xampp\htdocs\` e renomeie para `programec-api`:
   ```
   C:\xampp\htdocs\programec-api\
   ```
3. Abra o arquivo `programec-api\config\Banco.php` e preencha as credenciais do seu banco PostgreSQL:
   ```php
   $this->User     = "seu_usuario";
   $this->Password = "sua_senha";
   $this->Host     = "seu_host";
   ```
4. Teste um endpoint no navegador ou no Bruno:
   ```
   http://localhost/programec-api/endpoints/materias.php
   ```
   Deve retornar um JSON com as matérias cadastradas.

### Banco de dados

Execute o script SQL antes de rodar o app:

```
banco_dados/banco.sql
```

Rode no pgAdmin ou psql para criar as tabelas e inserir os dados iniciais.

### App Flutter

1. Certifique-se de ter o Flutter instalado (`flutter doctor`)
2. Entre na pasta do app:
   ```bash
   cd app_flutter
   ```
3. Instale as dependências:
   ```bash
   flutter pub get
   ```
4. Com o XAMPP rodando, execute o app:
   ```bash
   flutter run
   ```

> O app aponta para `http://localhost/programec-api/endpoints` — definido em `app_flutter/lib/services/api_service.dart`. Se testar em dispositivo físico, substitua `localhost` pelo IP da sua máquina na rede local.

## Informações do banco

**LINK:** https://github.com/HJcedo/PDM-Entrega-Francisco  
**BANCO:** postgres  
**ESQUEMA:** public
