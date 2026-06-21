import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/usuario.dart';
import '../models/materia.dart';
import '../models/exercicio.dart';

// URL base da API — trocar pelo IP do servidor da faculdade na fase de migração
const String _baseUrl =
    'http://200.19.1.19/20222GR.ADS0005/programec-api/endpoints';

class ApiService {
  // ── Cadastrar novo usuário ──────────────────────────────────────────────────
  // Retorna null em caso de sucesso, ou uma mensagem de erro
  static Future<String?> cadastrar(
    String nome,
    String email,
    String senha,
  ) async {
    final response = await http.post(
      Uri.parse('$_baseUrl/cadastro.php'),
      body: {'nome': nome, 'email': email, 'senha': senha},
    );
    final json = jsonDecode(response.body);
    if (json['NumMens'] == 1) return null;
    return json['Mensagem'];
  }

  // ── Login ──────────────────────────────────────────────────────────────────
  // Retorna o Usuario logado, ou lança uma exceção com a mensagem de erro
  static Future<Usuario> login(String email, String senha) async {
    final response = await http.post(
      Uri.parse('$_baseUrl/login.php'),
      body: {'email': email, 'senha': senha},
    );
    final json = jsonDecode(response.body);
    if (json['NumMens'] == 1) return Usuario.fromJson(json['dados']);
    throw Exception(json['Mensagem']);
  }

  // ── Buscar perfil ──────────────────────────────────────────────────────────
  static Future<Usuario> buscarPerfil(int id) async {
    final response = await http.get(Uri.parse('$_baseUrl/perfil.php?id=$id'));
    final json = jsonDecode(response.body);
    if (json['NumMens'] == 1) return Usuario.fromJson(json['dados']);
    throw Exception(json['Mensagem']);
  }

  // ── Atualizar nome e/ou avatar ─────────────────────────────────────────────
  static Future<String?> atualizarUsuario(
    int id, {
    String? nome,
    String? avatar,
  }) async {
    final body = <String, String>{'id': id.toString()};
    if (nome != null) body['nome'] = nome;
    if (avatar != null) body['avatar'] = avatar;

    final response = await http.post(
      Uri.parse('$_baseUrl/atualizar_usuario.php'),
      body: body,
    );
    final json = jsonDecode(response.body);
    if (json['NumMens'] == 1) return null;
    return json['Mensagem'];
  }

  // Exclui o usuário usando o endpoint já existente no servidor.
  static Future<String?> deletarUsuario(int id) async {
    final response = await http.post(
      Uri.parse('$_baseUrl/deletar_usuario.php'),
      body: {'id': id.toString()},
    );
    final json = jsonDecode(response.body);
    if (json['NumMens'] == 1) return null;
    return json['Mensagem'];
  }

  // ── Listar matérias ────────────────────────────────────────────────────────
  static Future<List<Materia>> listarMaterias() async {
    final response = await http.get(Uri.parse('$_baseUrl/materias.php'));
    final json = jsonDecode(response.body);
    if (json['NumMens'] == 1) {
      return (json['dados'] as List).map((m) => Materia.fromJson(m)).toList();
    }
    throw Exception(json['Mensagem']);
  }

  // ── Listar exercícios de uma matéria ───────────────────────────────────────
  static Future<List<Exercicio>> listarExercicios(int materiaId) async {
    final response = await http.get(
      Uri.parse('$_baseUrl/exercicios.php?materia_id=$materiaId'),
    );
    final json = jsonDecode(response.body);
    if (json['NumMens'] == 1) {
      return (json['dados'] as List).map((e) => Exercicio.fromJson(e)).toList();
    }
    throw Exception(json['Mensagem']);
  }

  // ── Salvar resultado do quiz ───────────────────────────────────────────────
  static Future<String?> salvarTentativa(
    int usuarioId,
    int materiaId,
    double nota,
  ) async {
    final response = await http.post(
      Uri.parse('$_baseUrl/tentativa.php'),
      body: {
        'usuario_id': usuarioId.toString(),
        'materia_id': materiaId.toString(),
        'nota': nota.toString(),
      },
    );
    final json = jsonDecode(response.body);
    if (json['NumMens'] == 1) return null;
    return json['Mensagem'];
  }
}
