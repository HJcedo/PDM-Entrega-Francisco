-- ============================================================
-- Programe.C - 04_seed_usuarios.sql
-- Insere/atualiza usuarios de teste para login no app.
-- ============================================================

-- Credencial:
-- aluno@programec.com / 123456

INSERT INTO usuario (id, nome, email, senha, avatar) VALUES
    (1, 'Aluno Teste', 'aluno@programec.com',
     '$2y$10$mmyEBOfAw0NcfW9KpeIoTeRnmgEaZcajLlHk38bRgjt//3Q83FQNS',
     '🧑‍💻')
ON CONFLICT (id) DO UPDATE SET
    nome = EXCLUDED.nome,
    email = EXCLUDED.email,
    senha = EXCLUDED.senha,
    avatar = EXCLUDED.avatar;

SELECT setval('usuario_id_seq', (SELECT MAX(id) FROM usuario));
