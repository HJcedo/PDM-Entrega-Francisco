# Programe.C - Banco de Dados

Esta pasta contem os scripts SQL necessarios para criar e popular o banco do app.

## Opcao simples

Rode `banco.sql` no Supabase SQL Editor, pgAdmin ou psql.

Esse arquivo apaga e recria as tabelas, entao use apenas em ambiente de desenvolvimento/teste.

## Opcao por etapas

Rode nesta ordem:

1. `01_schema.sql`
2. `02_seed_materias.sql`
3. `03_seed_exercicios.sql`
4. `04_seed_usuarios.sql`

## Tabelas

- `usuario`: usuarios cadastrados no app.
- `materia`: materias exibidas na Home.
- `exercicio`: perguntas do quiz.
- `tentativa`: resultado de cada quiz finalizado.

## Usuarios de teste

| email | senha |
| --- | --- |
| `aluno@programec.com` | `123456` |

## Logica de `opcoes_json`

A coluna `opcoes_json` depende do tipo da questao:

| tipo | opcoes_json | correta | codigo |
| --- | --- | --- | --- |
| `multipla_escolha` | Array JSON em texto | Indice da alternativa correta (`0`, `1`, `2`, `3`) | `NULL` |
| `verdadeiro_falso` | `NULL` | `0` para verdadeiro, `1` para falso | `NULL` |
| `completar_codigo` | `NULL` | Texto exato esperado | Codigo com `_____` |

Exemplo de multipla escolha:

```sql
opcoes_json = '["Opcao A","Opcao B","Opcao C","Opcao D"]'
correta = '0'
```

Exemplo de completar codigo:

```sql
codigo = 'SELECT _____ FROM usuarios;'
correta = '*'
```
