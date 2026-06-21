import 'package:flutter/material.dart';

import '../app_colors.dart';

class AppLogo extends StatelessWidget {
  final double fontSize;
  final bool onPrimary;

  const AppLogo({super.key, this.fontSize = 40, this.onPrimary = true});

  @override
  Widget build(BuildContext context) {
    final textColor = onPrimary ? Colors.white : const Color(0xFF172033);
    final badgeColor = onPrimary ? Colors.white : AppColors.primary;
    final badgeTextColor = onPrimary ? AppColors.primary : Colors.white;

    return Semantics(
      header: true,
      label: 'Programe ponto C',
      child: Row(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Text(
            'Programe.',
            style: TextStyle(
              fontSize: fontSize,
              fontWeight: FontWeight.w800,
              letterSpacing: -fontSize * 0.03,
              color: textColor,
            ),
          ),
          Container(
            margin: EdgeInsets.only(left: fontSize * 0.08),
            padding: EdgeInsets.symmetric(
              horizontal: fontSize * 0.25,
              vertical: fontSize * 0.1,
            ),
            decoration: BoxDecoration(
              color: badgeColor,
              borderRadius: BorderRadius.circular(fontSize * 0.3),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withValues(alpha: 0.1),
                  blurRadius: fontSize * 0.3,
                  offset: Offset(0, fontSize * 0.12),
                ),
              ],
            ),
            child: Text(
              'C',
              style: TextStyle(
                fontSize: fontSize * 0.78,
                height: 1,
                fontWeight: FontWeight.w900,
                color: badgeTextColor,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
