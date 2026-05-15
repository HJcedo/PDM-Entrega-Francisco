-- ============================================================
-- Script SQL - Programe.C
-- Banco de dados: postgres (Supabase / PostgreSQL)
-- Esquema: public
-- Rodar este arquivo no pgAdmin ou psql para criar as tabelas
-- ============================================================

-- Tabela de usuários do app
CREATE TABLE IF NOT EXISTS USUARIO (
    id     SERIAL PRIMARY KEY,
    nome   VARCHAR(100)  NOT NULL,
    email  VARCHAR(150)  UNIQUE NOT NULL,
    senha  VARCHAR(255)  NOT NULL,
    avatar VARCHAR(10)
);

-- Tabela de matérias (Banco de Dados, Redes, etc.)
CREATE TABLE IF NOT EXISTS MATERIA (
    id    SERIAL PRIMARY KEY,
    nome  VARCHAR(100) NOT NULL,
    icone VARCHAR(10)
);

-- Tabela de exercícios vinculados a uma matéria
-- Suporta 3 tipos: multipla_escolha | verdadeiro_falso | completar_codigo
CREATE TABLE IF NOT EXISTS EXERCICIO (
    id          SERIAL PRIMARY KEY,
    materia_id  INT  NOT NULL REFERENCES MATERIA(id),
    enunciado   TEXT NOT NULL,
    tipo        VARCHAR(30)  NOT NULL,
    opcoes_json TEXT,
    correta     VARCHAR(255) NOT NULL,
    codigo      TEXT
);

-- Tabela de tentativas (resultado de cada quiz feito por um usuário)
CREATE TABLE IF NOT EXISTS TENTATIVA (
    id         SERIAL PRIMARY KEY,
    usuario_id INT  NOT NULL REFERENCES USUARIO(id),
    materia_id INT  NOT NULL REFERENCES MATERIA(id),
    nota       NUMERIC(4,2),
    feita_em   TIMESTAMP DEFAULT NOW()
);

-- ============================================================
-- Dados iniciais: as 4 matérias do app
-- ============================================================
INSERT INTO MATERIA (nome, icone) VALUES
    ('Banco de Dados', '🗄️'),
    ('Redes',          '🌐'),
    ('Linguagem C',    '💻'),
    ('Algoritmos',     '🔢');

-- ============================================================
-- Exercícios de exemplo (materia_id = 1 = Banco de Dados)
-- ============================================================

-- Questão 1: múltipla escolha
INSERT INTO EXERCICIO (materia_id, enunciado, tipo, opcoes_json, correta, codigo)
VALUES (
    1,
    'O que é SQL?',
    'multipla_escolha',
    '["Linguagem de consulta estruturada","Sistema operacional","Protocolo de rede","Linguagem de programação orientada a objetos"]',
    '0',
    NULL
);

-- Questão 2: verdadeiro/falso
INSERT INTO EXERCICIO (materia_id, enunciado, tipo, opcoes_json, correta, codigo)
VALUES (
    1,
    'O protocolo UDP garante que os pacotes chegam na ordem correta.',
    'verdadeiro_falso',
    NULL,
    '1',
    NULL
);

-- Questão 3: completar código
INSERT INTO EXERCICIO (materia_id, enunciado, tipo, opcoes_json, correta, codigo)
VALUES (
    1,
    'Complete o comando SQL para buscar todos os registros:',
    'completar_codigo',
    NULL,
    '*',
    'SELECT _____ FROM usuarios;'
);
