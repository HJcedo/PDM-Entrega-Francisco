import 'dart:math';

import 'exercicio_screen.dart';
import 'perfil_screen.dart';
import '../common/app_imports.dart';

class HomeScreen extends StatefulWidget {
  final Usuario usuario;

  const HomeScreen({super.key, required this.usuario});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late final Future<List<Materia>> _materiasFuture;

  @override
  void initState() {
    super.initState();
    _materiasFuture = ApiService.listarMaterias();
  }

  void _abrirMateria(Materia materia) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) =>
            ExercicioScreen(materia: materia, usuario: widget.usuario),
      ),
    );
  }

  void _sortearMateria(List<Materia> materias) {
    if (materias.isEmpty) return;
    _abrirMateria(materias[Random().nextInt(materias.length)]);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F8FC),
      appBar: AppBar(
        backgroundColor: const Color(0xFFF5F8FC),
        surfaceTintColor: Colors.transparent,
        automaticallyImplyLeading: false,
        titleSpacing: 20,
        title: const AppLogo(fontSize: 23, onPrimary: false),
        actions: [
          Padding(
            padding: const EdgeInsets.only(right: 12),
            child: IconButton.filledTonal(
              tooltip: 'Abrir perfil',
              icon: const Icon(Icons.person_rounded),
              onPressed: () => Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => PerfilScreen(usuario: widget.usuario),
                ),
              ),
            ),
          ),
        ],
      ),
      body: FutureBuilder<List<Materia>>(
        future: _materiasFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(24),
                child: Text(
                  'Não foi possível carregar as matérias.\n${snapshot.error}',
                  textAlign: TextAlign.center,
                  style: const TextStyle(color: Colors.red),
                ),
              ),
            );
          }

          final materias = snapshot.data ?? [];
          if (materias.isEmpty) {
            return const Center(child: Text('Nenhuma matéria cadastrada.'));
          }

          return SafeArea(
            top: false,
            child: SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(20, 14, 20, 28),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Olá, ${widget.usuario.nome}! 👋',
                    style: const TextStyle(
                      color: Color(0xFF172033),
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      letterSpacing: -0.4,
                    ),
                  ),
                  const SizedBox(height: 4),
                  const Text(
                    'Escolha uma matéria para praticar',
                    style: TextStyle(color: Color(0xFF7B8496), fontSize: 15),
                  ),
                  const SizedBox(height: 22),
                  GridView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    gridDelegate:
                        const SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: 2,
                          crossAxisSpacing: 12,
                          mainAxisSpacing: 12,
                          childAspectRatio: 1.18,
                        ),
                    itemCount: materias.length,
                    itemBuilder: (context, index) {
                      final materia = materias[index];
                      return _MateriaCard(
                        materia: materia,
                        onTap: () => _abrirMateria(materia),
                      );
                    },
                  ),
                  const SizedBox(height: 24),
                  _ChallengeCard(onPressed: () => _sortearMateria(materias)),
                  const SizedBox(height: 26),
                  const Text(
                    'Como funciona',
                    style: TextStyle(
                      color: Color(0xFF172033),
                      fontSize: 18,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 12),
                  const Row(
                    children: [
                      Expanded(
                        child: _StepCard(
                          icon: Icons.grid_view_rounded,
                          number: '1',
                          label: 'Escolha',
                        ),
                      ),
                      SizedBox(width: 8),
                      Expanded(
                        child: _StepCard(
                          icon: Icons.quiz_rounded,
                          number: '2',
                          label: 'Responda',
                        ),
                      ),
                      SizedBox(width: 8),
                      Expanded(
                        child: _StepCard(
                          icon: Icons.trending_up_rounded,
                          number: '3',
                          label: 'Evolua',
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class _MateriaCard extends StatelessWidget {
  final Materia materia;
  final VoidCallback onTap;

  const _MateriaCard({required this.materia, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return Material(
      color: AppColors.primary,
      borderRadius: BorderRadius.circular(20),
      elevation: 2,
      shadowColor: AppColors.primary.withValues(alpha: 0.25),
      child: InkWell(
        borderRadius: BorderRadius.circular(20),
        onTap: onTap,
        child: Padding(
          padding: const EdgeInsets.all(14),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                width: 54,
                height: 54,
                decoration: BoxDecoration(
                  color: Colors.white.withValues(alpha: 0.16),
                  shape: BoxShape.circle,
                ),
                alignment: Alignment.center,
                child: Text(
                  materia.icone ?? '📚',
                  style: const TextStyle(fontSize: 30),
                ),
              ),
              const SizedBox(height: 10),
              Text(
                materia.nome,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                textAlign: TextAlign.center,
                style: const TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.w700,
                  fontSize: 14,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _ChallengeCard extends StatelessWidget {
  final VoidCallback onPressed;

  const _ChallengeCard({required this.onPressed});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF087FC1), Color(0xFF0D9DE8)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(22),
        boxShadow: [
          BoxShadow(
            color: AppColors.primary.withValues(alpha: 0.25),
            blurRadius: 18,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            width: 54,
            height: 54,
            decoration: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.16),
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.casino_rounded,
              color: Colors.white,
              size: 29,
            ),
          ),
          const SizedBox(width: 14),
          const Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Desafio rápido',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 17,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                SizedBox(height: 3),
                Text(
                  'Sorteie uma matéria e comece agora.',
                  style: TextStyle(color: Colors.white70, fontSize: 13),
                ),
              ],
            ),
          ),
          IconButton.filled(
            tooltip: 'Sortear matéria',
            style: IconButton.styleFrom(
              backgroundColor: Colors.white,
              foregroundColor: AppColors.primary,
            ),
            onPressed: onPressed,
            icon: const Icon(Icons.arrow_forward_rounded),
          ),
        ],
      ),
    );
  }
}

class _StepCard extends StatelessWidget {
  final IconData icon;
  final String number;
  final String label;

  const _StepCard({
    required this.icon,
    required this.number,
    required this.label,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE5EBF3)),
      ),
      child: Column(
        children: [
          Icon(icon, color: AppColors.primary, size: 24),
          const SizedBox(height: 7),
          Text(
            '$number. $label',
            maxLines: 1,
            style: const TextStyle(
              color: Color(0xFF4B5568),
              fontSize: 12,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}
