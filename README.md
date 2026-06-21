# Programe.C

Aplicativo educacional de quiz para estudantes de Tecnologia da Informação, desenvolvido como trabalho da disciplina de Programação para Dispositivos Móveis.

O aluno pode criar uma conta, entrar no aplicativo, escolher uma matéria, responder exercícios e acompanhar sua nota ao final. O projeto usa Flutter no frontend, PHP puro no backend e PostgreSQL no ambiente do IFsul.

## Funcionalidades

- Cadastro e login de usuários com senha protegida por bcrypt.
- Home com matérias carregadas do banco de dados.
- Sorteio de matéria pelo card **Desafio rápido**.
- Quiz com três tipos de exercício:
  - múltipla escolha;
  - verdadeiro ou falso;
  - completar código.
- Correção visual das respostas durante o quiz.
- Resultado com nota e quantidade de acertos.
- Salvamento das tentativas no banco.
- Perfil com seleção e atualização de avatar.
- Logout com retorno seguro à tela de login.
- Exclusão de conta e das tentativas relacionadas.

## Tecnologias

| Camada | Tecnologia |
| --- | --- |
| Aplicativo | Flutter / Dart / Material Design 3 |
| API | PHP puro com arquitetura modular |
| Banco de dados | PostgreSQL do IFsul |
| Comunicação | HTTP REST com JSON |
| Servidor local | XAMPP / Apache |
| Publicação remota | Servidor do IFsul via WinSCP |

## Arquitetura

```text
Flutter
  -> ApiService
  -> endpoints PHP de compatibilidade
  -> Controllers
  -> Repositories
  -> PostgreSQL do IFsul
```

O Flutter ainda consome os arquivos em `programec-api/endpoints/`. Esses arquivos são entradas de compatibilidade e encaminham as requisições para a arquitetura modular do backend.

## Estrutura do repositório

```text
PDM-Entrega-Francisco/
|-- app_flutter/
|   |-- lib/
|   |   |-- common/
|   |   |   |-- widgets/
|   |   |   |-- app_colors.dart
|   |   |   |-- app_feedback.dart
|   |   |   `-- app_imports.dart
|   |   |-- models/
|   |   |-- screens/
|   |   |-- services/
|   |   `-- main.dart
|   `-- README.md
|-- programec-api/
|   |-- app/
|   |   |-- Controllers/
|   |   `-- Repositories/
|   |-- config/
|   |-- core/
|   |-- endpoints/
|   |-- public/
|   |-- routes/
|   |-- teste/
|   `-- README.md
|-- banco_dados/
|   |-- banco.sql
|   |-- 01_schema.sql
|   |-- 02_seed_materias.sql
|   |-- 03_seed_exercicios.sql
|   |-- 04_seed_usuarios.sql
|   `-- README.md
`-- README.md
```

## Banco de dados

O ambiente atual usa exclusivamente o PostgreSQL do IFsul. As tabelas principais são:

```text
usuario   (id, nome, email, senha, avatar)
materia   (id, nome, icone)
exercicio (id, materia_id, enunciado, tipo, opcoes_json, correta, codigo)
tentativa (id, usuario_id, materia_id, nota, feita_em)
```

Os scripts SQL de criação e dados iniciais estão em `banco_dados/`.

> `banco.sql` apaga e recria as tabelas. Use esse arquivo somente em ambiente de desenvolvimento ou quando a recriação completa for realmente desejada.

Usuário utilizado nos testes do ambiente atual:

```text
email: joao@email.com
senha: 123456
```

## Tipos de exercício

| Tipo | Armazenamento e correção |
| --- | --- |
| `multipla_escolha` | `opcoes_json` guarda as alternativas e `correta` guarda o índice certo. |
| `verdadeiro_falso` | `correta` usa `0` para verdadeiro e `1` para falso. |
| `completar_codigo` | `codigo` contém a lacuna `_____` e `correta` guarda o texto esperado. |

## Executar o aplicativo

Pré-requisitos:

- Flutter SDK;
- Android Studio e um emulador Android, ou outro dispositivo compatível;
- acesso à API publicada no IFsul.

```powershell
cd C:\Users\macob\PDM-Entrega-Francisco\app_flutter
flutter pub get
flutter run
```

Para listar os dispositivos:

```powershell
flutter devices
```

A URL da API está em:

```text
app_flutter/lib/services/api_service.dart
```

O ambiente atual aponta para:

```text
http://200.19.1.19/20222GR.ADS0005/programec-api/endpoints
```

## Executar a API localmente

Copie `programec-api/` para:

```text
C:\xampp\htdocs\programec-api
```

Inicie o Apache e teste:

```text
http://localhost/programec-api/endpoints/materias.php
http://localhost/programec-api/public/index.php/materias
```

A conexão com o PostgreSQL do IFsul depende da disponibilidade da rede do ambiente acadêmico.

## Publicação no IFsul

Os arquivos PHP publicados no servidor do IFsul são atualizados manualmente por meio do WinSCP.

Sempre que houver alteração no backend:

1. identifique os arquivos PHP criados ou modificados;
2. conecte-se ao servidor pelo WinSCP;
3. envie os arquivos preservando a mesma estrutura de diretórios;
4. teste os endpoints remotos;
5. somente depois valide o fluxo correspondente no Flutter.

Mudanças exclusivamente no Flutter não exigem upload pelo WinSCP.

## Credenciais

As credenciais acadêmicas do PostgreSQL estão em `programec-api/config/Banco.php` para atender ao formato atual do trabalho. Em uma aplicação de produção, essas informações deveriam ser fornecidas por variáveis de ambiente e nunca versionadas no código.
