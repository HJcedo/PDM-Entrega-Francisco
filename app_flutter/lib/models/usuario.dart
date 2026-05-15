class Usuario {
  final int id;
  final String nome;
  final String email;
  final String? avatar;

  Usuario({
    required this.id,
    required this.nome,
    required this.email,
    this.avatar,
  });

  // Converte o JSON da API para um objeto Usuario
  factory Usuario.fromJson(Map<String, dynamic> json) {
    return Usuario(
      id:     int.parse(json['id'].toString()),
      nome:   json['nome'],
      email:  json['email'],
      avatar: json['avatar'],
    );
  }
}
