-- ============================================================
-- Programe.C - 03_seed_exercicios.sql
-- Recria os exercicios padrao do app.
--
-- Este seed apaga apenas os exercicios antes de inserir novamente.
-- ============================================================

DELETE FROM exercicio;

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

SELECT setval('exercicio_id_seq', (SELECT MAX(id) FROM exercicio));
