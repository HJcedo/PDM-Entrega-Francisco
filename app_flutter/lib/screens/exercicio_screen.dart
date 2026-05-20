import 'package:flutter/material.dart';
import 'resultado_screen.dart';
import '../models/exercicio.dart';
import '../models/materia.dart';
import '../models/usuario.dart';
import '../services/api_service.dart';

class ExercicioScreen extends StatefulWidget {
  final Materia materia;
  final Usuario usuario;

  const ExercicioScreen({
    super.key,
    required this.materia,
    required this.usuario,
  });

  @override
  State<ExercicioScreen> createState() => _ExercicioScreenState();
}

class _ExercicioScreenState extends State<ExercicioScreen> {
  final TextEditingController _controller = TextEditingController();
  final FocusNode _focusNode = FocusNode();

  List<Exercicio> _exercicios = [];
  bool _carregando = true;
  String? _erro;

  int _atual = 0;
  int _acertos = 0;
  int? _selecionada;
  bool _respondida = false;
  bool _salvandoTentativa = false;

  @override
  void initState() {
    super.initState();
    _carregarExercicios();
  }

  @override
  void dispose() {
    _controller.dispose();
    _focusNode.dispose();
    super.dispose();
  }

  Future<void> _carregarExercicios() async {
    setState(() {
      _carregando = true;
      _erro = null;
    });

    try {
      final exercicios = await ApiService.listarExercicios(widget.materia.id);
      if (!mounted) return;
      setState(() {
        _exercicios = exercicios;
        _carregando = false;
      });
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _erro = e.toString().replaceAll('Exception: ', '');
        _carregando = false;
      });
    }
  }

  void _responder(int index) {
    if (_respondida) return;

    final correta = int.tryParse(_exercicios[_atual].correta);

    setState(() {
      _selecionada = index;
      _respondida = true;
      if (index == correta) _acertos++;
    });
  }

  void _responderTexto() {
    if (_respondida) return;

    _focusNode.unfocus();
    final digitado = _controller.text.trim().toLowerCase();
    final correta = _exercicios[_atual].correta.trim().toLowerCase();

    setState(() {
      _respondida = true;
      if (digitado == correta) _acertos++;
    });
  }

  Future<void> _proxima() async {
    if (_atual < _exercicios.length - 1) {
      setState(() {
        _atual++;
        _selecionada = null;
        _respondida = false;
        _controller.clear();
      });
      return;
    }

    final nota = (_acertos / _exercicios.length) * 10;
    setState(() => _salvandoTentativa = true);

    try {
      final erro = await ApiService.salvarTentativa(
        widget.usuario.id,
        widget.materia.id,
        nota,
      );

      if (!mounted) return;
      if (erro != null) {
        _mostrarErro(erro);
      }
    } catch (_) {
      if (!mounted) return;
      _mostrarErro('Não foi possível salvar a tentativa.');
    } finally {
      if (mounted) {
        setState(() => _salvandoTentativa = false);
      }
    }

    if (!mounted) return;
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(
        builder: (_) => ResultadoScreen(
          materia: widget.materia,
          nota: nota,
          acertos: _acertos,
          total: _exercicios.length,
          usuario: widget.usuario,
        ),
      ),
    );
  }

  void _mostrarErro(String mensagem) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(mensagem), backgroundColor: Colors.red.shade700),
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_carregando) {
      return Scaffold(
        appBar: AppBar(title: Text(widget.materia.nome)),
        body: const Center(child: CircularProgressIndicator()),
      );
    }

    if (_erro != null) {
      return Scaffold(
        appBar: AppBar(title: Text(widget.materia.nome)),
        body: Center(
          child: Padding(
            padding: const EdgeInsets.all(24),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  _erro!,
                  textAlign: TextAlign.center,
                  style: const TextStyle(color: Colors.red),
                ),
                const SizedBox(height: 16),
                ElevatedButton(
                  onPressed: _carregarExercicios,
                  child: const Text('Tentar novamente'),
                ),
              ],
            ),
          ),
        ),
      );
    }

    if (_exercicios.isEmpty) {
      return Scaffold(
        appBar: AppBar(title: Text(widget.materia.nome)),
        body: const Center(
          child: Text('Nenhum exercício cadastrado para esta matéria.'),
        ),
      );
    }

    final exercicio = _exercicios[_atual];

    return Scaffold(
      appBar: AppBar(title: Text(widget.materia.nome)),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            LinearProgressIndicator(
              value: (_atual + 1) / _exercicios.length,
              backgroundColor: Colors.grey[300],
              color: const Color(0xFF1CB0F6),
              minHeight: 8,
              borderRadius: BorderRadius.circular(4),
            ),
            const SizedBox(height: 8),
            Text(
              'Pergunta ${_atual + 1} de ${_exercicios.length}',
              style: const TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 24),
            Text(
              exercicio.enunciado,
              style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 24),
            Expanded(
              child: SingleChildScrollView(
                child: _buildAreaResposta(exercicio),
              ),
            ),
            if (_respondida)
              Padding(
                padding: const EdgeInsets.only(top: 16),
                child: SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(vertical: 16),
                      backgroundColor: const Color(0xFF1CB0F6),
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    onPressed: _salvandoTentativa ? null : _proxima,
                    child: _salvandoTentativa
                        ? const SizedBox(
                            height: 20,
                            width: 20,
                            child: CircularProgressIndicator(strokeWidth: 2),
                          )
                        : Text(
                            _atual < _exercicios.length - 1
                                ? 'Próxima'
                                : 'Ver resultado',
                            style: const TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildAreaResposta(Exercicio exercicio) {
    if (exercicio.tipo == 'multipla_escolha') {
      return _buildMultiplaEscolha(exercicio);
    } else if (exercicio.tipo == 'verdadeiro_falso') {
      return _buildVerdadeiroFalso(exercicio);
    } else if (exercicio.tipo == 'completar_codigo') {
      return _buildCompletarCodigo(exercicio);
    }

    return const Text('Tipo de exercício não reconhecido.');
  }

  Widget _buildMultiplaEscolha(Exercicio exercicio) {
    final opcoes = exercicio.opcoesJson ?? [];
    final correta = int.tryParse(exercicio.correta);

    if (opcoes.isEmpty) {
      return const Text('Este exercício não possui opções cadastradas.');
    }

    return Column(
      children: List.generate(opcoes.length, (i) {
        Color cor = Colors.white;
        Color bordaCor = Colors.grey.shade300;

        if (_respondida && i == correta) {
          cor = Colors.green.shade100;
          bordaCor = Colors.green;
        } else if (_respondida && i == _selecionada) {
          cor = Colors.red.shade100;
          bordaCor = Colors.red;
        }

        return GestureDetector(
          onTap: () => _responder(i),
          child: Container(
            width: double.infinity,
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: cor,
              border: Border.all(color: bordaCor, width: 2),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Text(opcoes[i], style: const TextStyle(fontSize: 16)),
          ),
        );
      }),
    );
  }

  Widget _buildVerdadeiroFalso(Exercicio exercicio) {
    final correta = int.tryParse(exercicio.correta);

    Color corBotao(int indice) {
      if (!_respondida) return Colors.white;
      if (indice == correta) return Colors.green.shade100;
      if (indice == _selecionada) return Colors.red.shade100;
      return Colors.white;
    }

    Color corBorda(int indice) {
      if (!_respondida) return Colors.grey.shade300;
      if (indice == correta) return Colors.green;
      if (indice == _selecionada) return Colors.red;
      return Colors.grey.shade300;
    }

    return Row(
      children: [
        Expanded(
          child: GestureDetector(
            onTap: () => _responder(0),
            child: Container(
              height: 80,
              decoration: BoxDecoration(
                color: corBotao(0),
                border: Border.all(color: corBorda(0), width: 2),
                borderRadius: BorderRadius.circular(12),
              ),
              child: const Center(
                child: Text(
                  'VERDADEIRO',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  textAlign: TextAlign.center,
                ),
              ),
            ),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: GestureDetector(
            onTap: () => _responder(1),
            child: Container(
              height: 80,
              decoration: BoxDecoration(
                color: corBotao(1),
                border: Border.all(color: corBorda(1), width: 2),
                borderRadius: BorderRadius.circular(12),
              ),
              child: const Center(
                child: Text(
                  'FALSO',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  textAlign: TextAlign.center,
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildCompletarCodigo(Exercicio exercicio) {
    final codigo = exercicio.codigo ?? '';
    final correta = exercicio.correta;

    Color bordaCampo = Colors.grey.shade300;
    if (_respondida) {
      final digitado = _controller.text.trim().toLowerCase();
      bordaCampo = (digitado == correta.toLowerCase())
          ? Colors.green
          : Colors.red;
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          width: double.infinity,
          padding: const EdgeInsets.all(16),
          margin: const EdgeInsets.only(bottom: 20),
          decoration: BoxDecoration(
            color: const Color(0xFF1E1E1E),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Text(
            codigo,
            style: const TextStyle(
              fontFamily: 'monospace',
              color: Colors.white,
              fontSize: 16,
              letterSpacing: 0.5,
            ),
          ),
        ),
        TextField(
          controller: _controller,
          focusNode: _focusNode,
          enabled: !_respondida,
          style: const TextStyle(
            fontFamily: 'monospace',
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
          decoration: InputDecoration(
            hintText: 'Digite o que falta no código...',
            filled: true,
            fillColor: Colors.white,
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide(color: bordaCampo, width: 2),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide(color: bordaCampo, width: 2),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: const BorderSide(color: Color(0xFF1CB0F6), width: 2),
            ),
            disabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide(color: bordaCampo, width: 2),
            ),
          ),
          onSubmitted: (_) => _responderTexto(),
        ),
        if (!_respondida)
          Padding(
            padding: const EdgeInsets.only(top: 12),
            child: SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  backgroundColor: const Color(0xFF1CB0F6),
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                onPressed: _responderTexto,
                child: const Text(
                  'Confirmar',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ),
        if (_respondida)
          Padding(
            padding: const EdgeInsets.only(top: 16),
            child: Builder(
              builder: (context) {
                final acertou =
                    _controller.text.trim().toLowerCase() ==
                    correta.toLowerCase();
                return Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(14),
                  decoration: BoxDecoration(
                    color: acertou ? Colors.green.shade50 : Colors.red.shade50,
                    border: Border.all(
                      color: acertou ? Colors.green : Colors.red,
                      width: 1.5,
                    ),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: acertou
                      ? const Text(
                          '✅ Correto!',
                          style: TextStyle(
                            color: Colors.green,
                            fontWeight: FontWeight.bold,
                            fontSize: 15,
                          ),
                        )
                      : Text(
                          '❌ Resposta correta: $correta',
                          style: const TextStyle(
                            color: Colors.red,
                            fontWeight: FontWeight.bold,
                            fontSize: 15,
                            fontFamily: 'monospace',
                          ),
                        ),
                );
              },
            ),
          ),
      ],
    );
  }
}
