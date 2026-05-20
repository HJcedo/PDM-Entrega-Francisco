import 'package:flutter/material.dart';
import '../models/usuario.dart';
import '../services/api_service.dart';

class PerfilScreen extends StatefulWidget {
  final Usuario usuario;
  const PerfilScreen({super.key, required this.usuario});

  @override
  State<PerfilScreen> createState() => _PerfilScreenState();
}

class _PerfilScreenState extends State<PerfilScreen> {
  final List<String> _avatares = ['🧑‍💻', '👩‍💻', '🤖', '🦊', '🐧', '🎮'];

  late Usuario _usuario;
  int _avatarSelecionado = 0;
  bool _carregando = true;
  bool _salvando = false;

  @override
  void initState() {
    super.initState();
    _usuario = widget.usuario;
    _definirAvatarSelecionado(_usuario.avatar);
    _carregarPerfil();
  }

  Future<void> _carregarPerfil() async {
    try {
      final usuarioAtualizado = await ApiService.buscarPerfil(
        widget.usuario.id,
      );
      if (!mounted) return;
      setState(() {
        _usuario = usuarioAtualizado;
        _definirAvatarSelecionado(usuarioAtualizado.avatar);
        _carregando = false;
      });
    } catch (_) {
      if (!mounted) return;
      setState(() => _carregando = false);
      _mostrarMensagem('Não foi possível carregar o perfil.', erro: true);
    }
  }

  Future<void> _salvarAvatar() async {
    setState(() => _salvando = true);

    final avatar = _avatares[_avatarSelecionado];
    try {
      final erro = await ApiService.atualizarUsuario(
        _usuario.id,
        avatar: avatar,
      );

      if (!mounted) return;
      if (erro != null) {
        _mostrarMensagem(erro, erro: true);
        return;
      }

      setState(() {
        _usuario = Usuario(
          id: _usuario.id,
          nome: _usuario.nome,
          email: _usuario.email,
          avatar: avatar,
        );
      });
      _mostrarMensagem('Avatar salvo com sucesso!');
    } catch (_) {
      if (!mounted) return;
      _mostrarMensagem('Não foi possível salvar o avatar.', erro: true);
    } finally {
      if (mounted) setState(() => _salvando = false);
    }
  }

  void _definirAvatarSelecionado(String? avatar) {
    final indice = _avatares.indexOf(avatar ?? '');
    _avatarSelecionado = indice >= 0 ? indice : 0;
  }

  void _mostrarMensagem(String mensagem, {bool erro = false}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(mensagem),
        backgroundColor: erro ? Colors.red.shade700 : Colors.green,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Meu Perfil')),
      body: _carregando
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(24),
              child: Column(
                children: [
                  Text(
                    _avatares[_avatarSelecionado],
                    style: const TextStyle(fontSize: 72),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    _usuario.nome,
                    style: const TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  Text(
                    _usuario.email,
                    style: const TextStyle(color: Colors.grey),
                  ),
                  const SizedBox(height: 32),
                  const Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Escolher avatar',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Wrap(
                    spacing: 12,
                    runSpacing: 12,
                    children: List.generate(_avatares.length, (i) {
                      final selecionado = i == _avatarSelecionado;
                      return GestureDetector(
                        onTap: () => setState(() => _avatarSelecionado = i),
                        child: Container(
                          width: 86,
                          height: 86,
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
                    }),
                  ),
                  const SizedBox(height: 24),
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
                      onPressed: _salvando ? null : _salvarAvatar,
                      child: _salvando
                          ? const SizedBox(
                              height: 20,
                              width: 20,
                              child: CircularProgressIndicator(strokeWidth: 2),
                            )
                          : const Text(
                              'Salvar avatar',
                              style: TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                    ),
                  ),
                ],
              ),
            ),
    );
  }
}
