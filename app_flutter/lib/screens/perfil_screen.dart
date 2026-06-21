import 'login_screen.dart';
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
  bool _excluindo = false;

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
      AppFeedback.showError(context, 'Não foi possível carregar o perfil.');
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
      AppFeedback.showError(context, 'Não foi possível salvar o avatar.');
    } finally {
      if (mounted) setState(() => _salvando = false);
    }
  }

  void _definirAvatarSelecionado(String? avatar) {
    final indice = _avatares.indexOf(avatar ?? '');
    _avatarSelecionado = indice >= 0 ? indice : 0;
  }

  void _voltarParaLogin() {
    Navigator.of(context).pushAndRemoveUntil(
      MaterialPageRoute(builder: (_) => const LoginScreen()),
      (route) => false,
    );
  }

  Future<void> _confirmarLogout() async {
    final confirmar = await showDialog<bool>(
      context: context,
      builder: (dialogContext) => AlertDialog(
        icon: const Icon(Icons.logout_rounded, color: AppColors.primary),
        title: const Text('Sair da conta?'),
        content: const Text(
          'Você voltará para a tela de login.',
          textAlign: TextAlign.center,
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext, false),
            child: const Text('Cancelar'),
          ),
          FilledButton(
            onPressed: () => Navigator.pop(dialogContext, true),
            child: const Text('Sair'),
          ),
        ],
      ),
    );

    if (confirmar == true && mounted) {
      _voltarParaLogin();
    }
  }

  Future<void> _confirmarExclusao() async {
    final confirmar = await showDialog<bool>(
      context: context,
      builder: (dialogContext) => AlertDialog(
        icon: const Icon(Icons.delete_forever_rounded, color: Colors.red),
        title: const Text('Excluir sua conta?'),
        content: const Text(
          'Esta ação é permanente. Seu perfil e suas tentativas serão '
          'removidos e não poderão ser recuperados.',
          textAlign: TextAlign.center,
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext, false),
            child: const Text('Cancelar'),
          ),
          FilledButton(
            style: FilledButton.styleFrom(backgroundColor: Colors.red),
            onPressed: () => Navigator.pop(dialogContext, true),
            child: const Text('Excluir conta'),
          ),
        ],
      ),
    );

    if (confirmar != true || !mounted) return;

    setState(() => _excluindo = true);
    try {
      final erro = await ApiService.deletarUsuario(_usuario.id);
      if (!mounted) return;

      if (erro != null) {
        AppFeedback.showError(context, erro);
        return;
      }

      _voltarParaLogin();
    } catch (_) {
      if (!mounted) return;
      AppFeedback.showError(
        context,
        'Não foi possível excluir a conta. Tente novamente.',
      );
    } finally {
      if (mounted) setState(() => _excluindo = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Meu Perfil')),
      body: _carregando
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(24, 16, 24, 32),
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
                    onPressed: _excluindo ? null : _salvarAvatar,
                  ),
                  const SizedBox(height: 30),
                  const Divider(),
                  const SizedBox(height: 14),
                  const Align(
                    alignment: Alignment.centerLeft,
                    child: Text(
                      'Conta',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    child: OutlinedButton.icon(
                      onPressed: _excluindo ? null : _confirmarLogout,
                      icon: const Icon(Icons.logout_rounded),
                      label: const Text('Sair da conta'),
                      style: OutlinedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        foregroundColor: const Color(0xFF4B5568),
                        side: const BorderSide(color: Color(0xFFD8DEE8)),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),
                  SizedBox(
                    width: double.infinity,
                    child: TextButton.icon(
                      onPressed: _excluindo ? null : _confirmarExclusao,
                      icon: _excluindo
                          ? const SizedBox(
                              width: 18,
                              height: 18,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                color: Colors.red,
                              ),
                            )
                          : const Icon(Icons.delete_outline_rounded),
                      label: Text(
                        _excluindo ? 'Excluindo conta...' : 'Excluir conta',
                      ),
                      style: TextButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        foregroundColor: Colors.red.shade700,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
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
