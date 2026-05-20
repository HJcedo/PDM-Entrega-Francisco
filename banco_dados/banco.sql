-- ============================================================
-- Programe.C - Banco completo para ambiente de teste
-- PostgreSQL / Supabase
--
-- ATENCAO: este script apaga e recria as tabelas do app.
-- Use em ambiente de desenvolvimento/teste.
-- ============================================================

DROP TABLE IF EXISTS tentativa CASCADE;
DROP TABLE IF EXISTS exercicio CASCADE;
DROP TABLE IF EXISTS materia CASCADE;
DROP TABLE IF EXISTS usuario CASCADE;

CREATE TABLE usuario (
    id     SERIAL PRIMARY KEY,
    nome   VARCHAR(100) NOT NULL,
    email  VARCHAR(150) UNIQUE NOT NULL,
    senha  VARCHAR(255) NOT NULL,
    avatar VARCHAR(20)
);

CREATE TABLE materia (
    id    SERIAL PRIMARY KEY,
    nome  VARCHAR(100) UNIQUE NOT NULL,
    icone VARCHAR(20)
);

CREATE TABLE exercicio (
    id          SERIAL PRIMARY KEY,
    materia_id  INT NOT NULL REFERENCES materia(id),
    enunciado   TEXT NOT NULL,
    tipo        VARCHAR(30) NOT NULL,
    opcoes_json TEXT,
    correta     VARCHAR(255) NOT NULL,
    codigo      TEXT,
    CONSTRAINT ck_exercicio_tipo
        CHECK (tipo IN ('multipla_escolha', 'verdadeiro_falso', 'completar_codigo'))
);

CREATE TABLE tentativa (
    id         SERIAL PRIMARY KEY,
    usuario_id INT NOT NULL REFERENCES usuario(id),
    materia_id INT NOT NULL REFERENCES materia(id),
    nota       NUMERIC(4,2),
    feita_em   TIMESTAMP DEFAULT NOW(),
    CONSTRAINT ck_tentativa_nota
        CHECK (nota IS NULL OR (nota >= 0 AND nota <= 10))
);

INSERT INTO materia (id, nome, icone) VALUES
    (1, 'Banco de Dados', '🗄️'),
    (2, 'Redes', '🌐'),
    (3, 'Linguagem C', '💻'),
    (4, 'Algoritmos', '🔢');

SELECT setval('materia_id_seq', (SELECT MAX(id) FROM materia));

-- Usuarios de teste.
-- Senha:
-- - aluno@programec.com -> 123456

INSERT INTO usuario (id, nome, email, senha, avatar) VALUES
    (1, 'Aluno Teste', 'aluno@programec.com',
     '$2y$10$mmyEBOfAw0NcfW9KpeIoTeRnmgEaZcajLlHk38bRgjt//3Q83FQNS',
     '🧑‍💻');

SELECT setval('usuario_id_seq', (SELECT MAX(id) FROM usuario));

-- Regra dos campos:
-- - multipla_escolha: opcoes_json tem array JSON; correta tem indice.
-- - verdadeiro_falso: opcoes_json fica NULL; correta usa 0=Verdadeiro, 1=Falso.
-- - completar_codigo: codigo tem _____; correta tem o texto esperado.

INSERT INTO exercicio (materia_id, enunciado, tipo, opcoes_json, correta, codigo) VALUES
-- Banco de Dados
(1, 'O que e SQL?', 'multipla_escolha',
 '["Linguagem de consulta estruturada","Sistema operacional","Protocolo de rede","Editor de texto"]',
 '0', NULL),
(1, 'Uma chave primaria identifica de forma unica cada registro de uma tabela.', 'verdadeiro_falso',
 NULL, '0', NULL),
(1, 'Complete o comando SQL para buscar todos os registros:', 'completar_codigo',
 NULL, '*', 'SELECT _____ FROM usuarios;'),

-- Redes
(2, 'Qual protocolo e usado para acessar paginas web de forma segura?', 'multipla_escolha',
 '["FTP","HTTPS","SMTP","DHCP"]',
 '1', NULL),
(2, 'O protocolo UDP garante entrega e ordem dos pacotes.', 'verdadeiro_falso',
 NULL, '1', NULL),
(2, 'Complete o endereco local padrao:', 'completar_codigo',
 NULL, '127.0.0.1', 'localhost = _____;'),

-- Linguagem C
(3, 'Qual funcao e usada para imprimir texto na tela em C?', 'multipla_escolha',
 '["scanf","printf","main","return"]',
 '1', NULL),
(3, 'Em C, a funcao main e o ponto de entrada do programa.', 'verdadeiro_falso',
 NULL, '0', NULL),
(3, 'Complete a declaracao de uma variavel inteira:', 'completar_codigo',
 NULL, 'int', '_____ idade;'),

-- Algoritmos
(4, 'Qual estrutura repete comandos enquanto uma condicao for verdadeira?', 'multipla_escolha',
 '["if","while","switch","return"]',
 '1', NULL),
(4, 'Um algoritmo e uma sequencia de passos para resolver um problema.', 'verdadeiro_falso',
 NULL, '0', NULL),
(4, 'Complete a palavra usada para testar uma condicao:', 'completar_codigo',
 NULL, 'if', '_____ (idade >= 18) { }');
