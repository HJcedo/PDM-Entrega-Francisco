class Tentativa {
  final int id;
  final int usuarioId;
  final int materiaId;
  final double nota;
  final String feitaEm;

  Tentativa({
    required this.id,
    required this.usuarioId,
    required this.materiaId,
    required this.nota,
    required this.feitaEm,
  });

  // Converte o JSON da API para um objeto Tentativa
  factory Tentativa.fromJson(Map<String, dynamic> json) {
    return Tentativa(
      id:         int.parse(json['id'].toString()),
      usuarioId:  int.parse(json['usuario_id'].toString()),
      materiaId:  int.parse(json['materia_id'].toString()),
      nota:       double.parse(json['nota'].toString()),
      feitaEm:    json['feita_em'],
    );
  }
}
