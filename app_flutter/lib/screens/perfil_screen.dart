import '../common/app_imports.dart';

class PerfilScreen extends StatefulWidget {
  final Usuario usuario;
  const PerfilScreen({super.key, required this.usuario});

  @override
  State<PerfilScreen> createState() => _PerfilScreenState();
}

class _PerfilScreenState extends State<PerfilScreen> {
  final List<String> _avatares = [
    '\u{1F9D1}\u{200D}\u{1F4BB}',
    '\u{1F469}\u{200D}\u{1F4BB}',
    '\u{1F916}',
    '\u{1F98A}',
    '\u{1F427}',
    '\u{1F3AE}',
  ];

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
      AppFeedback.showError(context, 'Nao foi possivel carregar o perfil.');
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
        AppFeedback.showError(context, erro);
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
      AppFeedback.showSuccess(context, 'Avatar salvo com sucesso!');
    } catch (_) {
      if (!mounted) return;
      AppFeedback.showError(context, 'Nao foi possivel salvar o avatar.');
    } finally {
      if (mounted) setState(() => _salvando = false);
    }
  }

  void _definirAvatarSelecionado(String? avatar) {
    final indice = _avatares.indexOf(avatar ?? '');
    _avatarSelecionado = indice >= 0 ? indice : 0;
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
                                  ? AppColors.primary
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
                  AppButton(
                    text: 'Salvar avatar',
                    isLoading: _salvando,
                    onPressed: _salvarAvatar,
                  ),
                ],
              ),
            ),
    );
  }
}
