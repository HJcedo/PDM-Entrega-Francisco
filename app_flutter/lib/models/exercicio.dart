class Exercicio {
  final int id;
  final int materiaId;
  final String enunciado;
  final String tipo;
  final List<String>? opcoesJson; // lista de opções para múltipla escolha
  final String correta;           // índice ("0","1"...) ou texto ("*")
  final String? codigo;           // bloco de código com _____ para completar

  Exercicio({
    required this.id,
    required this.materiaId,
    required this.enunciado,
    required this.tipo,
    this.opcoesJson,
    required this.correta,
    this.codigo,
  });

  // Converte o JSON da API para um objeto Exercicio
  factory Exercicio.fromJson(Map<String, dynamic> json) {
    // opcoes_json já vem como lista do PHP (o endpoint já decodifica o JSON)
    List<String>? opcoes;
    if (json['opcoes_json'] != null) {
      opcoes = List<String>.from(json['opcoes_json']);
    }

    return Exercicio(
      id:         int.parse(json['id'].toString()),
      materiaId:  int.parse(json['materia_id'].toString()),
      enunciado:  json['enunciado'],
      tipo:       json['tipo'],
      opcoesJson: opcoes,
      correta:    json['correta'],
      codigo:     json['codigo'],
    );
  }
}
