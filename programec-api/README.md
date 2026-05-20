# Programe.C - API

API REST em PHP puro para o app Programe.C. Ela conecta o Flutter ao banco PostgreSQL/Supabase.

## Estrutura

```text
programec-api/
|-- config/
|   `-- Banco.php
|-- endpoints/
|   |-- cadastro.php
|   |-- login.php
|   |-- perfil.php
|   |-- atualizar_usuario.php
|   |-- deletar_usuario.php
|   |-- materias.php
|   |-- exercicios.php
|   `-- tentativa.php
`-- bruno/
```

## Endpoints

| Metodo | Endpoint | Funcao |
| --- | --- | --- |
| POST | `/endpoints/cadastro.php` | Cadastra usuario com senha criptografada. |
| POST | `/endpoints/login.php` | Autentica usuario e retorna seus dados. |
| GET | `/endpoints/perfil.php?id=X` | Busca perfil do usuario. |
| POST | `/endpoints/atualizar_usuario.php` | Atualiza nome e/ou avatar. |
| POST | `/endpoints/deletar_usuario.php` | Remove usuario e suas tentativas. |
| GET | `/endpoints/materias.php` | Lista materias. |
| GET | `/endpoints/exercicios.php?materia_id=X` | Lista exercicios de uma materia. |
| POST | `/endpoints/tentativa.php` | Salva resultado do quiz. |

## Resposta padrao

```json
{
  "NumMens": 1,
  "Mensagem": "Descricao do resultado",
  "registros": 1,
  "dados": {}
}
```

`NumMens` vale `1` para sucesso e `0` para erro.

## Banco

Tabelas usadas:

```text
usuario
materia
exercicio
tentativa
```

Os scripts ficam em `../banco_dados/`.

## Como rodar no XAMPP

Copie esta pasta para:

```text
C:\xampp\htdocs\programec-api
```

Inicie o Apache e teste:

```text
http://localhost/programec-api/endpoints/materias.php
```

## Observacao sobre implementacao

A API usa PHP puro, sem framework, e organiza cada operacao em um endpoint separado.
