# Programe.C - API

API REST em PHP puro para o app Programe.C. Esta branch organiza o backend no formato modular usado nas orientacoes do professor.

## Estrutura

```text
programec-api/
|-- config/
|   |-- Banco.php
|   `-- Database.php
|-- app/
|   |-- Controllers/
|   |   |-- UsuarioController.php
|   |   |-- MateriaController.php
|   |   |-- ExercicioController.php
|   |   `-- TentativaController.php
|   `-- Repositories/
|       |-- UsuarioRepository.php
|       |-- MateriaRepository.php
|       |-- ExercicioRepository.php
|       `-- TentativaRepository.php
|-- routes/
|   |-- usuario_routes.php
|   |-- materia_routes.php
|   |-- exercicio_routes.php
|   `-- tentativa_routes.php
|-- core/
|   |-- bootstrap.php
|   `-- Response.php
|-- public/
|   |-- index.php
|   `-- .htaccess
|-- endpoints/
`-- bruno/
```

## Fluxo

```text
Flutter -> public/index.php -> routes -> app/Controllers -> app/Repositories -> banco
```

Os arquivos em `endpoints/` foram mantidos como compatibilidade com o app Flutter atual. Eles apenas chamam os controllers da estrutura modular.

O arquivo `core/bootstrap.php` centraliza os `require_once` globais e os headers da API.

## Rotas Modulares

| Metodo | Rota | Funcao |
| --- | --- | --- |
| POST | `/public/index.php/cadastro` | Cadastra usuario com senha criptografada. |
| POST | `/public/index.php/login` | Autentica usuario e retorna seus dados. |
| GET | `/public/index.php/perfil?id=X` | Busca perfil do usuario. |
| POST | `/public/index.php/atualizar-usuario` | Atualiza nome e/ou avatar. |
| POST | `/public/index.php/deletar-usuario` | Remove usuario e suas tentativas. |
| GET | `/public/index.php/materias` | Lista materias. |
| GET | `/public/index.php/exercicios?materia_id=X` | Lista exercicios de uma materia. |
| POST | `/public/index.php/tentativa` | Salva resultado do quiz. |

## Endpoints de Compatibilidade

| Metodo | Endpoint | Funcao |
| --- | --- | --- |
| POST | `/endpoints/cadastro.php` | Cadastra usuario. |
| POST | `/endpoints/login.php` | Autentica usuario. |
| GET | `/endpoints/perfil.php?id=X` | Busca perfil do usuario. |
| POST | `/endpoints/atualizar_usuario.php` | Atualiza perfil. |
| POST | `/endpoints/deletar_usuario.php` | Remove usuario. |
| GET | `/endpoints/materias.php` | Lista materias. |
| GET | `/endpoints/exercicios.php?materia_id=X` | Lista exercicios. |
| POST | `/endpoints/tentativa.php` | Salva resultado. |

## Resposta Padrao

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

Esta branch usa o PostgreSQL do IFsul:

```text
host: 192.168.20.18
porta: 5432
banco: franciscozanela
usuario: franciscozanela
```

Tabelas usadas:

```text
usuario
materia
exercicio
tentativa
```

Os scripts ficam em `../banco_dados/`.

Usuario de teste:

```text
email: joao@email.com
senha: 123456
```

## Como Rodar no XAMPP

Copie esta pasta para:

```text
C:\xampp\htdocs\programec-api
```

Inicie o Apache e teste a rota modular:

```text
http://localhost/programec-api/public/index.php/materias
```

Ou teste a rota mantida para compatibilidade:

```text
http://localhost/programec-api/endpoints/materias.php
```

Quando a API estiver publicada no servidor do IFsul, o endpoint remoto fica:

```text
http://200.19.1.19/20222GR.ADS0005/programec-api/endpoints/materias.php
```
