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

## Informações do banco

**LINK:** https://github.com/HJcedo/PDM-Entrega-Francisco  
**BANCO:** postgres  
**ESQUEMA:** public
