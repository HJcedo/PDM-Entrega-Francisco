import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/usuario.dart';
import '../models/materia.dart';
import '../models/exercicio.dart';

const String _baseUrl =
    'http://200.19.1.19/20222GR.ADS0005/programec-api/public';

const Map<String, String> _jsonHeaders = {
  'Content-Type': 'application/json; charset=UTF-8',
};

class ApiService {
  // Transforma a resposta JSON em um Map do Dart.
  static dynamic _json(http.Response response) {
    return jsonDecode(utf8.decode(response.bodyBytes));
  }

  static Future<String?> cadastrar(
    String nome,
    String email,
    String senha,
  ) async {
    final response = await http.post(
      Uri.parse('$_baseUrl/usuarios'),
      headers: _jsonHeaders,
      body: jsonEncode({'nome': nome, 'email': email, 'senha': senha}),
    );

    final json = _json(response);
    return json['NumMens'] == 1 ? null : json['Mensagem'];
  }

  static Future<Usuario> login(String email, String senha) async {
    final response = await http.post(
      Uri.parse('$_baseUrl/sessoes'),
      headers: _jsonHeaders,
      body: jsonEncode({'email': email, 'senha': senha}),
    );

    final json = _json(response);

    if (json['NumMens'] == 1) {
      return Usuario.fromJson(json['dados']);
    }

    throw Exception(json['Mensagem']);
  }

  static Future<Usuario> buscarPerfil(int id) async {
    final response = await http.get(Uri.parse('$_baseUrl/usuarios/$id'));
    final json = _json(response);
    return Usuario.fromJson(json['dados']);
  }

  static Future<String?> atualizarUsuario(
    int id, {
    required String avatar,
  }) async {
    final response = await http.patch(
      Uri.parse('$_baseUrl/usuarios/$id'),
      headers: _jsonHeaders,
      body: jsonEncode({'avatar': avatar}),
    );

    final json = _json(response);
    return json['NumMens'] == 1 ? null : json['Mensagem'];
  }

  static Future<String?> deletarUsuario(int id) async {
    final response = await http.delete(Uri.parse('$_baseUrl/usuarios/$id'));
    final json = _json(response);
    return json['NumMens'] == 1 ? null : json['Mensagem'];
  }

  static Future<List<Materia>> listarMaterias() async {
    final response = await http.get(Uri.parse('$_baseUrl/materias'));
    final json = _json(response);

    return (json['dados'] as List)
        .map((materia) => Materia.fromJson(materia))
        .toList();
  }

  static Future<List<Exercicio>> listarExercicios(int materiaId) async {
    final response = await http.get(
      Uri.parse('$_baseUrl/materias/$materiaId/exercicios'),
    );
    final json = _json(response);

    return (json['dados'] as List)
        .map((exercicio) => Exercicio.fromJson(exercicio))
        .toList();
  }

  static Future<String?> salvarTentativa(
    int usuarioId,
    int materiaId,
    double nota,
  ) async {
    final response = await http.post(
      Uri.parse('$_baseUrl/tentativas'),
      headers: _jsonHeaders,
      body: jsonEncode({
        'usuario_id': usuarioId,
        'materia_id': materiaId,
        'nota': nota,
      }),
    );

    final json = _json(response);
    return json['NumMens'] == 1 ? null : json['Mensagem'];
  }
}
