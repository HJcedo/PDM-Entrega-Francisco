class Materia {
  final int id;
  final String nome;
  final String? icone;

  Materia({
    required this.id,
    required this.nome,
    this.icone,
  });

  // Converte o JSON da API para um objeto Materia
  factory Materia.fromJson(Map<String, dynamic> json) {
    return Materia(
      id:    int.parse(json['id'].toString()),
      nome:  json['nome'],
      icone: json['icone'],
    );
  }
}
