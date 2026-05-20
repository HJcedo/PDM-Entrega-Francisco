-- ============================================================
-- Programe.C - 02_seed_materias.sql
-- Insere/atualiza as materias padrao do app.
-- ============================================================

INSERT INTO materia (id, nome, icone) VALUES
    (1, 'Banco de Dados', '🗄️'),
    (2, 'Redes', '🌐'),
    (3, 'Linguagem C', '💻'),
    (4, 'Algoritmos', '🔢')
ON CONFLICT (id) DO UPDATE SET
    nome = EXCLUDED.nome,
    icone = EXCLUDED.icone;

SELECT setval('materia_id_seq', (SELECT MAX(id) FROM materia));
