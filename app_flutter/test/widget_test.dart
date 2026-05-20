import 'package:flutter_test/flutter_test.dart';
import 'package:programec/main.dart';

void main() {
  testWidgets('mostra tela de login', (WidgetTester tester) async {
    await tester.pumpWidget(const ProgrameCApp());

    expect(find.text('Programe.C'), findsOneWidget);
    expect(find.text('Entrar'), findsOneWidget);
  });
}
