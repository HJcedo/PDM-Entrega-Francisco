# Programe.C — Flutter App

App mobile educacional de quiz para estudantes de TI. O aluno escolhe uma matéria, responde exercícios de múltipla escolha, verdadeiro/falso ou completar código, e recebe uma nota ao final.

> **Backend:** [programe.c API](https://github.com/HJcedo/PDM-PHP) — PHP + PostgreSQL (Supabase)

---

## Telas

| Tela | Descrição |
|------|-----------|
| Login | Autenticação com e-mail e senha |
| Cadastro | Criação de nova conta |
| Home | Grid com as matérias disponíveis |
| Exercício | Quiz com 3 tipos de questão |
| Resultado | Nota final com acertos/erros |
| Perfil | Nome, e-mail e escolha de avatar |

---

## Estrutura do projeto

```
lib/
├── main.dart                  # Entry point e configuração do tema
├── models/
│   ├── usuario.dart           # Classe Usuario com fromJson()
│   ├── materia.dart           # Classe Materia com fromJson()
│   ├── exercicio.dart         # Classe Exercicio com fromJson()
│   └── tentativa.dart         # Classe Tentativa com fromJson()
├── screens/
│   ├── login_screen.dart
│   ├── cadastro_screen.dart
│   ├── home_screen.dart
│   ├── exercicio_screen.dart
│   ├── resultado_screen.dart
│   └── perfil_screen.dart
└── services/
    └── api_service.dart       # Todas as chamadas HTTP à API
```

---

## Stack

- **Flutter** (Dart) — Material Design 3
- **Pacote http** — chamadas REST à API
- Cor primária: `#1CB0F6`

---

## Como rodar

### Pré-requisitos
- [Flutter SDK](https://flutter.dev/docs/get-started/install) instalado
- Backend da API rodando (ver [programe.c API](https://github.com/HJcedo/PDM-PHP))

### Passos

```bash
# 1. Clone o repositório
git clone https://github.com/HJcedo/PDM-Flutter.git
cd programec-flutter

# 2. Instale as dependências
flutter pub get

# 3. Configure a URL da API em lib/services/api_service.dart
# Altere a constante _baseUrl para o endereço do seu servidor

# 4. Rode o app
flutter run
```

### Configurar a URL da API

Abra `lib/services/api_service.dart` e altere a linha:

```dart
const String _baseUrl = 'http://localhost/programec-api/endpoints';
```

| Ambiente | URL |
|----------|-----|
| Localhost (XAMPP) | `http://localhost/programec-api/endpoints` |
| Emulador Android | `http://10.0.2.2/programec-api/endpoints` |
| Servidor da faculdade | `http://<IP-do-servidor>/endpoints` |

---

## Tipos de questão

| Tipo | Como funciona |
|------|---------------|
| `multipla_escolha` | 4 opções clicáveis — verde/vermelho após responder |
| `verdadeiro_falso` | 2 botões grandes lado a lado |
| `completar_codigo` | Bloco de código com lacuna + campo de digitação livre |
