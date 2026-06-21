# Programe.C — Aplicativo Flutter

Frontend mobile do Programe.C, um aplicativo educacional de quiz para estudantes de Tecnologia da Informação.

O aplicativo consome a API PHP publicada no servidor do IFsul e apresenta login, cadastro, seleção de matérias, exercícios, resultado e gerenciamento do perfil.

## Funcionalidades

- Login e cadastro.
- Home com matérias vindas do PostgreSQL.
- Identidade visual reutilizável do Programe.C.
- Card **Desafio rápido** para sortear uma matéria.
- Exercícios de múltipla escolha, verdadeiro ou falso e completar código.
- Feedback visual de acerto e erro.
- Resultado com nota final.
- Escolha e atualização de avatar.
- Logout.
- Exclusão da conta pelo endpoint já existente.

## Estrutura

```text
lib/
|-- common/
|   |-- widgets/
|   |   |-- app_button.dart
|   |   |-- app_logo.dart
|   |   `-- app_text_field.dart
|   |-- app_colors.dart
|   |-- app_feedback.dart
|   `-- app_imports.dart
|-- models/
|   |-- exercicio.dart
|   |-- materia.dart
|   |-- tentativa.dart
|   `-- usuario.dart
|-- screens/
|   |-- cadastro_screen.dart
|   |-- exercicio_screen.dart
|   |-- home_screen.dart
|   |-- login_screen.dart
|   |-- perfil_screen.dart
|   `-- resultado_screen.dart
|-- services/
|   `-- api_service.dart
`-- main.dart
```

### Responsabilidades

- `common/`: cores, mensagens e componentes visuais compartilhados.
- `models/`: conversão dos objetos recebidos em JSON.
- `screens/`: telas e fluxo de navegação.
- `services/api_service.dart`: todas as requisições HTTP para a API.

## Fluxo do aplicativo

```text
Tela Flutter
  -> ApiService
  -> endpoint PHP
  -> resposta JSON
  -> model Dart
  -> atualização da interface
```

O aplicativo não mantém uma conexão aberta com o banco. Cada ação faz uma requisição HTTP independente para a API.

## API utilizada

A URL atual está definida em `lib/services/api_service.dart`:

```dart
const String _baseUrl =
    'http://200.19.1.19/20222GR.ADS0005/programec-api/endpoints';
```

Endpoints consumidos:

| Método | Endpoint | Uso |
| --- | --- | --- |
| POST | `cadastro.php` | Criar conta. |
| POST | `login.php` | Autenticar usuário. |
| GET | `perfil.php?id=X` | Carregar perfil. |
| POST | `atualizar_usuario.php` | Atualizar nome ou avatar. |
| POST | `deletar_usuario.php` | Excluir conta e tentativas. |
| GET | `materias.php` | Listar matérias. |
| GET | `exercicios.php?materia_id=X` | Listar exercícios. |
| POST | `tentativa.php` | Salvar resultado. |

O logout é local: o aplicativo remove as rotas anteriores e retorna para a tela de login.

## Executar

Pré-requisitos:

- Flutter SDK;
- Android Studio com Android SDK;
- emulador Android ou dispositivo físico;
- API do IFsul disponível.

```powershell
cd C:\Users\macob\PDM-Entrega-Francisco\app_flutter
flutter pub get
flutter doctor
flutter devices
flutter run
```

Para executar em um dispositivo específico:

```powershell
flutter run -d ID_DO_DISPOSITIVO
```

## Hot reload e dados remotos

O hot reload preserva o estado atual dos widgets. Alterações feitas diretamente no banco, como trocar o ícone de uma matéria, podem exigir:

- hot restart (`R` no terminal);
- sair e entrar novamente na tela;
- fechar e abrir o aplicativo.

## Validação

Execute:

```powershell
flutter analyze
```

## Stack visual

- Flutter e Dart.
- Material Design 3.
- Cor principal: `#1CB0F6`.
- Componentes compartilhados para botões, campos e logotipo.

## Observação sobre o backend

Mudanças no Flutter não exigem WinSCP. Caso um novo recurso dependa de alteração ou criação de endpoint PHP, os arquivos correspondentes precisam ser enviados manualmente ao servidor do IFsul antes do teste no aplicativo.
