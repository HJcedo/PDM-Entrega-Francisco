import 'home_screen.dart';
import '../common/app_imports.dart';

class ResultadoScreen extends StatelessWidget {
  final Materia materia;
  final double nota;
  final int acertos;
  final int total;
  final Usuario usuario;

  const ResultadoScreen({
    super.key,
    required this.materia,
    required this.nota,
    required this.acertos,
    required this.total,
    required this.usuario,
  });

  @override
  Widget build(BuildContext context) {
    final aprovado = nota >= 6;

    return Scaffold(
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(32),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(
                aprovado ? '\u{1F389}' : '\u{1F605}',
                style: const TextStyle(fontSize: 64),
              ),
              const SizedBox(height: 16),
              Text(
                aprovado ? 'Parabens!' : 'Continue praticando!',
                style: const TextStyle(
                  fontSize: 28,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                materia.nome,
                style: const TextStyle(fontSize: 16, color: Colors.grey),
              ),
              const SizedBox(height: 32),
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: AppColors.primary.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Column(
                  children: [
                    Text(
                      nota.toStringAsFixed(1),
                      style: TextStyle(
                        fontSize: 56,
                        fontWeight: FontWeight.bold,
                        color: aprovado ? Colors.green : Colors.red,
                      ),
                    ),
                    Text(
                      '$acertos de $total acertos',
                      style: const TextStyle(color: Colors.grey, fontSize: 16),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 40),
              AppButton(
                text: 'Voltar ao inicio',
                onPressed: () => Navigator.pushAndRemoveUntil(
                  context,
                  MaterialPageRoute(
                    builder: (_) => HomeScreen(usuario: usuario),
                  ),
                  (route) => false,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
