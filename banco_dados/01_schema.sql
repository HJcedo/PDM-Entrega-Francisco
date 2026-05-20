-- ============================================================
-- Programe.C - 01_schema.sql
-- Cria as tabelas do app sem inserir dados.
-- ============================================================

CREATE TABLE IF NOT EXISTS usuario (
    id     SERIAL PRIMARY KEY,
    nome   VARCHAR(100) NOT NULL,
    email  VARCHAR(150) UNIQUE NOT NULL,
    senha  VARCHAR(255) NOT NULL,
    avatar VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS materia (
    id    SERIAL PRIMARY KEY,
    nome  VARCHAR(100) UNIQUE NOT NULL,
    icone VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS exercicio (
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

CREATE TABLE IF NOT EXISTS tentativa (
    id         SERIAL PRIMARY KEY,
    usuario_id INT NOT NULL REFERENCES usuario(id),
    materia_id INT NOT NULL REFERENCES materia(id),
    nota       NUMERIC(4,2),
    feita_em   TIMESTAMP DEFAULT NOW(),
    CONSTRAINT ck_tentativa_nota
        CHECK (nota IS NULL OR (nota >= 0 AND nota <= 10))
);
