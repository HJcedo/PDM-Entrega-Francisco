import 'package:flutter/material.dart';
import '../models/usuario.dart';

class PerfilScreen extends StatefulWidget {
  final Usuario usuario;
  const PerfilScreen({super.key, required this.usuario});

  @override
  State<PerfilScreen> createState() => _PerfilScreenState();
}

class _PerfilScreenState extends State<PerfilScreen> {
  int _avatarSelecionado = 0;

  final List<String> _avatares = ['🧑‍💻', '👩‍💻', '🤖', '🦊', '🐧', '🎮'];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Meu Perfil')),
      body: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            Text(
              _avatares[_avatarSelecionado],
              style: const TextStyle(fontSize: 72),
            ),
            const SizedBox(height: 8),
            Text(
              widget.usuario.nome,
              style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            Text(
              widget.usuario.email,
              style: const TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 32),
            const Align(
              alignment: Alignment.centerLeft,
              child: Text(
                'Escolher avatar',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
              ),
            ),
            const SizedBox(height: 12),
            GridView.builder(
              shrinkWrap: true,
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 3,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
              ),
              itemCount: _avatares.length,
              itemBuilder: (context, i) {
                final selecionado = i == _avatarSelecionado;
                return GestureDetector(
                  onTap: () => setState(() => _avatarSelecionado = i),
                  child: Container(
                    decoration: BoxDecoration(
                      border: Border.all(
                        color: selecionado
                            ? const Color(0xFF1CB0F6)
                            : Colors.grey.shade300,
                        width: selecionado ? 3 : 1,
                      ),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Center(
                      child: Text(
                        _avatares[i],
                        style: const TextStyle(fontSize: 32),
                      ),
                    ),
                  ),
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}
