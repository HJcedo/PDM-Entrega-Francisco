# Programe.C — API

API REST em PHP puro para o app mobile [Programe.C](https://github.com/HJcedo/PDM-Flutter). Gerencia usuários, matérias, exercícios e tentativas de quiz.

> **Frontend:** [programe.c Flutter](https://github.com/HJcedo/PDM-Flutter)

---

## Endpoints

### Usuário
| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/endpoints/cadastro.php` | Cria novo usuário |
| POST | `/endpoints/login.php` | Autentica e retorna dados do usuário |
| GET | `/endpoints/perfil.php?id=X` | Busca perfil pelo ID |
| POST | `/endpoints/atualizar_usuario.php` | Atualiza nome e/ou avatar |
| POST | `/endpoints/deletar_usuario.php` | Remove usuário e suas tentativas |

### Matérias e Exercícios
| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/endpoints/materias.php` | Lista todas as matérias |
| GET | `/endpoints/exercicios.php?materia_id=X` | Lista exercícios de uma matéria |

### Quiz
| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/endpoints/tentativa.php` | Salva resultado de um quiz |

### Formato de resposta (padrão)
```json
{
  "NumMens": 1,
  "Mensagem": "Descrição do resultado",
  "registros": 1,
  "dados": { }
}
```
`NumMens`: `1` = sucesso, `0` = erro

---

## Estrutura do projeto

```
programe.c api/
├── config/
│   └── Banco.php          # Conexão PDO com PostgreSQL + headers CORS
├── endpoints/
│   ├── cadastro.php
│   ├── login.php
│   ├── perfil.php
│   ├── atualizar_usuario.php
│   ├── deletar_usuario.php
│   ├── materias.php
│   ├── exercicios.php
│   └── tentativa.php
├── bruno/
│   └── novo bruno/        # Collection Bruno para testar os endpoints
└── banco.sql              # Script para criar as tabelas e dados iniciais
```

---

## Stack

- **PHP 8** puro (sem frameworks)
- **PDO** para conexão segura com o banco
- **PostgreSQL** (Supabase como banco temporário)
- Senhas com **bcrypt** via `password_hash()`

---

## Como rodar

### Pré-requisitos
- [XAMPP](https://www.apachefriends.org/) com Apache e PHP 8+
- Banco PostgreSQL (ou conta no [Supabase](https://supabase.com))

### Passos

```bash
# 1. Clone o repositório dentro do htdocs do XAMPP
cd C:/xampp/htdocs
git clone https://github.com/HJcedo/PDM-PHP.git programec-api
```

**2. Crie as tabelas no banco** — rode o `banco.sql` no pgAdmin ou no SQL Editor do Supabase.

**3. Configure a conexão** — abra `config/Banco.php` e altere as credenciais:

```php
$this->User     = "seu_usuario";
$this->Password = "sua_senha";
$this->Database = "seu_banco";
$this->Host     = "seu_host";
$this->Porta    = "5432";
```

**4. Inicie o Apache** no XAMPP Control Panel.

**5. Teste** abrindo no browser:
```
http://localhost/programec-api/endpoints/materias.php
```

---

## Banco de dados

```sql
USUARIO  (id, nome, email, senha, avatar)
MATERIA  (id, nome, icone)
EXERCICIO(id, materia_id, enunciado, tipo, opcoes_json, correta, codigo)
TENTATIVA(id, usuario_id, materia_id, nota, feita_em)
```

### Tipos de exercício
| Tipo | Campos usados |
|------|--------------|
| `multipla_escolha` | `opcoes_json` (array), `correta` (índice "0","1"...) |
| `verdadeiro_falso` | `correta` ("0"=Verdadeiro, "1"=Falso) |
| `completar_codigo` | `codigo` (texto com _____), `correta` (resposta exata) |

---

## Testando com Bruno

Abra o [Bruno](https://www.usebruno.com/) e importe a pasta `bruno/novo bruno/` como collection. Os requests já estão configurados na ordem correta: Criar → Login → Buscar → Atualizar → Deletar.
