import 'package:flutter/material.dart';
import 'home_screen.dart';
import '../models/usuario.dart';

class ResultadoScreen extends StatelessWidget {
  final String materia;
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
                aprovado ? '🎉' : '😅',
                style: const TextStyle(fontSize: 64),
              ),
              const SizedBox(height: 16),
              Text(
                aprovado ? 'Parabéns!' : 'Continue praticando!',
                style: const TextStyle(
                  fontSize: 28,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                materia,
                style: const TextStyle(fontSize: 16, color: Colors.grey),
              ),
              const SizedBox(height: 32),
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: const Color(0xFF1CB0F6).withValues(alpha: 0.1),
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
              SizedBox(
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
                  onPressed: () => Navigator.pushAndRemoveUntil(
                    context,
                    MaterialPageRoute(builder: (_) => HomeScreen(usuario: usuario)),
                    (route) => false,
                  ),
                  child: const Text(
                    'Voltar ao início',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
