import '../common/app_imports.dart';

class CadastroScreen extends StatefulWidget {
  const CadastroScreen({super.key});

  @override
  State<CadastroScreen> createState() => _CadastroScreenState();
}

class _CadastroScreenState extends State<CadastroScreen> {
  final _nomeController = TextEditingController();
  final _emailController = TextEditingController();
  final _senhaController = TextEditingController();
  bool _carregando = false;

  @override
  void dispose() {
    _nomeController.dispose();
    _emailController.dispose();
    _senhaController.dispose();
    super.dispose();
  }

  Future<void> _cadastrar() async {
    final nome = _nomeController.text.trim();
    final email = _emailController.text.trim();
    final senha = _senhaController.text.trim();

    if (nome.isEmpty || email.isEmpty || senha.isEmpty) {
      AppFeedback.showError(context, 'Preencha todos os campos.');
      return;
    }

    setState(() => _carregando = true);

    try {
      final erro = await ApiService.cadastrar(nome, email, senha);

      if (!mounted) return;
      if (erro != null) {
        AppFeedback.showError(context, erro);
        return;
      }

      AppFeedback.showSuccess(context, 'Conta criada com sucesso! Faca login.');
      Navigator.pop(context);
    } catch (e) {
      if (!mounted) return;
      AppFeedback.showError(context, 'Erro ao cadastrar. Tente novamente.');
    } finally {
      if (mounted) setState(() => _carregando = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.primary,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(32),
          child: Column(
            children: [
              const Text(
                'Criar conta',
                style: TextStyle(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
              const SizedBox(height: 32),
              AppTextField(
                controller: _nomeController,
                label: 'Nome',
              ),
              const SizedBox(height: 16),
              AppTextField(
                controller: _emailController,
                label: 'Email',
                keyboardType: TextInputType.emailAddress,
              ),
              const SizedBox(height: 16),
              AppTextField(
                controller: _senhaController,
                label: 'Senha',
                obscureText: true,
              ),
              const SizedBox(height: 24),
              AppButton(
                text: 'Cadastrar',
                isLoading: _carregando,
                backgroundColor: Colors.white,
                foregroundColor: AppColors.primary,
                onPressed: _cadastrar,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
