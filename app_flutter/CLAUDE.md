# Programe.C — Contexto do Projeto

## O que é este projeto

Aplicativo educacional mobile chamado **Programe.C**, desenvolvido em Flutter. O objetivo é ensinar disciplinas de TI (Tecnologia da Informação) de forma interativa através de exercícios de múltipla escolha com feedback visual e sistema de notas.

Contexto acadêmico: projeto para disciplina de programação mobile e futuro TCC. O estudante precisa entender todo o código desenvolvido, pois haverá prova oral com o professor. **Sempre explique o que cada trecho de código faz, de forma clara e didática.**

---

## Stack tecnológica

| Camada | Tecnologia |
|--------|-----------|
| Frontend | Flutter (Dart), Material Design 3 |
| Backend | PHP puro (sem frameworks) |
| Banco de dados | PostgreSQL |
| Comunicação | REST API (JSON) |

**Cor primária do app**: `#1CB0F6` (azul)

---

## Estrutura de pastas

### Flutter (`lib/`)
```
lib/
├── main.dart                  # Entry point do app
├── models/                    # Classes de dados (Usuario, Materia, etc.)
├── screens/                   # Telas do app (uma por arquivo)
│   ├── login_screen.dart
│   ├── cadastro_screen.dart
│   ├── home_screen.dart
│   ├── exercicio_screen.dart
│   ├── resultado_screen.dart
│   └── perfil_screen.dart
└── services/                  # Comunicação com a API (HTTP)
    └── api_service.dart
```

> **Situação atual**: todo o código ainda está em `lib/main.dart` (monolítico). A separação em pastas é parte do roadmap.

### Backend PHP (`programe.c api/`)
```
programe.c api/
├── config/
│   └── Banco.php              # Conexão PDO com PostgreSQL (Supabase temporariamente)
├── endpoints/
│   ├── cadastro.php           # POST — registrar novo usuário
│   ├── login.php              # POST — autenticar usuário
│   ├── perfil.php             # GET  — buscar dados do usuário por id
│   ├── atualizar_usuario.php  # POST — atualizar nome e/ou avatar
│   ├── deletar_usuario.php    # POST — remover usuário e suas tentativas
│   ├── materias.php           # GET  — listar todas as matérias
│   ├── exercicios.php         # GET  — listar exercícios por materia_id
│   └── tentativa.php         # POST — salvar resultado de um quiz
├── banco.sql                  # Script SQL para criar as tabelas e dados iniciais
└── bruno/
    └── novo bruno/            # Collection Bruno para testar os endpoints
```

> **Situação atual**: backend criado e funcionando. Conectado ao Supabase (banco temporário).
> O XAMPP serve os arquivos de `C:\xampp\htdocs\programec-api\` — ao editar arquivos no Desktop, copiar para o htdocs também.

---

## Banco de dados (PostgreSQL — Supabase)

> **Banco temporário**: Supabase (`aws-1-sa-east-1.pooler.supabase.com`) enquanto o banco da faculdade não está acessível. Quando migrar, restaurar o `setHost()` no `Banco.php` com os IPs da faculdade.

### Diagrama ER (resumo)

```
USUARIO  (id, nome, email, senha, avatar)
MATERIA  (id, nome, icone)
EXERCICIO(id, materia_id, enunciado, tipo, opcoes_json, correta, codigo)
TENTATIVA(id, usuario_id, materia_id, nota, feita_em)
```

### Relacionamentos
- `USUARIO` (1) → (N) `TENTATIVA`
- `MATERIA` (1) → (N) `EXERCICIO`
- `MATERIA` (1) → (N) `TENTATIVA`
- `TENTATIVA` referencia tanto `USUARIO` quanto `MATERIA`

---

## Telas existentes

| Tela | Arquivo | Status |
|------|---------|--------|
| Login | `LoginScreen` em main.dart | Fake (sem validação real) |
| Cadastro | `CadastroScreen` em main.dart | Fake |
| Home (lista de matérias) | `HomeScreen` em main.dart | Dados hardcoded |
| Exercícios (quiz) | `ExercicioScreen` em main.dart | 3 questões hardcoded com 3 tipos: `multipla_escolha`, `verdadeiro_falso`, `completar_codigo` |
| Resultado | `ResultadoScreen` em main.dart | Funcional (sem salvar no banco) |
| Perfil | `PerfilScreen` em main.dart | Avatar local (não persiste) |

---

## Casos de uso

1. Fazer cadastro
2. Fazer login
3. Escolher matéria
4. Realizar exercícios (quiz de múltipla escolha)
5. Ver resultado com nota (0–10)
6. Atualizar perfil e escolher avatar

---

## Roadmap de desenvolvimento

### Fase 1 — Backend PHP + banco de dados ✅ CONCLUÍDA
- [x] Criar script SQL para as tabelas no PostgreSQL
- [x] Criar `config/Banco.php` com conexão PDO ao PostgreSQL (Supabase)
- [x] Endpoint `POST /cadastro.php` — registrar novo usuário
- [x] Endpoint `POST /login.php` — autenticar e retornar dados do usuário
- [x] Endpoint `GET /perfil.php?id=X` — buscar perfil do usuário
- [x] Endpoint `POST /atualizar_usuario.php` — atualizar nome e/ou avatar
- [x] Endpoint `POST /deletar_usuario.php` — remover usuário e tentativas
- [x] Endpoint `GET /materias.php` — listar matérias
- [x] Endpoint `GET /exercicios.php?materia_id=X` — listar exercícios de uma matéria
- [x] Endpoint `POST /tentativa.php` — salvar resultado de um quiz
- [x] Collection Bruno para testar todos os endpoints

### Fase 2 — Refatorar Flutter
- [ ] Separar `main.dart` em arquivos por tela (`screens/`)
- [ ] Criar models: `Usuario`, `Materia`, `Exercicio`, `Tentativa`
- [ ] Adicionar pacote `http` no `pubspec.yaml`
- [ ] Criar `services/api_service.dart` com as chamadas HTTP
- [ ] Substituir dados hardcoded por chamadas reais à API

### Fase 3 — Integração completa
- [ ] Login e cadastro funcionando com banco real
- [ ] Matérias e exercícios carregados da API
- [ ] Resultado do quiz salvo como tentativa no banco
- [ ] Perfil: nome e avatar salvos e recuperados do banco
- [ ] Migrar API do servidor externo para o servidor da faculdade

---

## Diretrizes para desenvolvimento

### Geral
- O estudante é **iniciante em PHP** e tem conhecimento **básico de Flutter**
- **Sempre explique** o que cada função, classe ou bloco de código faz
- Prefira código simples e legível a código "elegante" e difícil de entender
- Nunca faça mudanças grandes sem explicar o raciocínio por trás

### PHP
- Usar **PHP puro** (sem frameworks como Laravel ou Symfony)
- Usar **PDO** para conexão com o banco (mais seguro que mysqli)
- Sempre usar **prepared statements** para evitar SQL injection
- Respostas da API sempre em **JSON**
- Usar `password_hash()` e `password_verify()` para senhas

### Flutter
- Seguir o padrão de separação: `models/`, `screens/`, `services/`
- Um arquivo por tela
- Usar `StatefulWidget` quando a tela tem estado (ex: quiz), `StatelessWidget` quando não tem
- Usar o pacote `http` para chamadas à API

#### Tipos de questão implementados em `ExercicioScreen`

Cada questão na lista `exercicios` tem um campo `'tipo'` que controla qual widget é exibido. O método `_buildAreaResposta()` faz o desvio com `if/else if`:

| Tipo | Campo extra | Como funciona |
|------|-------------|---------------|
| `multipla_escolha` | `opcoes`, `correta` | Lista de 4 opções clicáveis |
| `verdadeiro_falso` | `correta` (0=V, 1=F) | Dois botões grandes lado a lado |
| `completar_codigo` | `codigo`, `correta` (String) | Bloco de código com lacuna + campo de digitação livre |

O estado da tela usa apenas 4 variáveis: `_atual`, `_acertos`, `_selecionada`, `_respondida`.

### Hospedagem
- Backend será desenvolvido em servidor externo primeiro
- Depois migrado para o servidor da faculdade
- O IP/URL do servidor deve ser configurável (variável no `api_service.dart`)
