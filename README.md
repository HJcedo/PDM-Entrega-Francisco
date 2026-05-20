# Programe.C

Aplicativo educacional de quiz para estudantes de TI, desenvolvido como trabalho da disciplina de Programacao para Dispositivos Moveis.

O objetivo do projeto e permitir que o aluno faca login, escolha uma materia, responda exercicios e veja sua nota ao final. O app usa Flutter no frontend, PHP puro na API e PostgreSQL/Supabase no banco de dados.

## Funcionalidades

- Cadastro e login de usuario com senha criptografada usando bcrypt.
- Home com materias carregadas do banco.
- Quiz com tres tipos de exercicio:
  - multipla escolha
  - verdadeiro/falso
  - completar codigo
- Resultado com nota e quantidade de acertos.
- Salvamento da tentativa no banco.
- Perfil com avatar salvo no banco.

## Tecnologias

| Camada | Tecnologia |
| --- | --- |
| App | Flutter / Dart |
| API | PHP puro |
| Banco | PostgreSQL / Supabase |
| Comunicacao | HTTP REST com JSON |
| Servidor local | XAMPP / Apache |

## Estrutura

```text
PDM-Entrega-Francisco/
|-- app_flutter/
|   `-- lib/
|       |-- main.dart
|       |-- models/
|       |-- screens/
|       `-- services/
|-- programec-api/
|   |-- config/
|   |-- endpoints/
|   `-- bruno/
|-- banco_dados/
|   |-- banco.sql
|   |-- 01_schema.sql
|   |-- 02_seed_materias.sql
|   |-- 03_seed_exercicios.sql
|   |-- 04_seed_usuarios.sql
|   `-- README.md
`-- README.md
```

## Como o app funciona

1. O Flutter mostra as telas e captura as acoes do usuario.
2. A classe `ApiService` faz chamadas HTTP para a API PHP.
3. Os endpoints PHP consultam ou alteram o banco usando PDO.
4. A API devolve JSON.
5. O Flutter transforma esse JSON em objetos como `Usuario`, `Materia` e `Exercicio`.

## Banco de dados

As tabelas principais sao:

```text
usuario   (id, nome, email, senha, avatar)
materia   (id, nome, icone)
exercicio (id, materia_id, enunciado, tipo, opcoes_json, correta, codigo)
tentativa (id, usuario_id, materia_id, nota, feita_em)
```

Para criar e popular o banco, rode o arquivo:

```text
banco_dados/banco.sql
```

Esse script recria as tabelas e insere materias, exercicios e um usuario de teste. Use em ambiente de desenvolvimento/teste.

Usuario de teste:

```text
email: aluno@programec.com
senha: 123456
```

## Logica de `opcoes_json`

A tabela `exercicio` guarda os tres tipos de questao no mesmo lugar. O campo `tipo` informa como o Flutter deve montar a pergunta.

| tipo | Como funciona |
| --- | --- |
| `multipla_escolha` | Usa `opcoes_json` com as alternativas e `correta` com o indice da resposta certa. |
| `verdadeiro_falso` | Nao usa `opcoes_json`; `correta` guarda `0` para verdadeiro e `1` para falso. |
| `completar_codigo` | Usa `codigo` com a lacuna `_____` e compara a resposta digitada com `correta`. |

Essa escolha permite usar uma unica tabela para todos os tipos de exercicio.

## Como rodar a API

1. Copie a pasta `programec-api/` para:

```text
C:\xampp\htdocs\programec-api
```

2. Inicie o Apache no XAMPP.

3. Teste no navegador:

```text
http://localhost/programec-api/endpoints/materias.php
```

Outro teste util:

```text
http://localhost/programec-api/endpoints/exercicios.php?materia_id=1
```

## Como rodar o app Flutter

Entre na pasta do app:

```powershell
cd C:\Users\Acer\PDM-Entrega-Francisco\app_flutter
```

Instale as dependencias:

```powershell
flutter pub get
```

Rode no Windows:

```powershell
flutter run -d windows
```

O app aponta para:

```text
http://localhost/programec-api/endpoints
```

Essa URL fica em:

```text
app_flutter/lib/services/api_service.dart
```

## Observacao sobre credenciais

As credenciais do banco estao no arquivo `programec-api/config/Banco.php` para facilitar a execucao local do projeto. Em um projeto real, essas informacoes deveriam ficar fora do codigo, por exemplo em variaveis de ambiente.
