<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Решает систему линейных уравнений методом Гаусса
 */
function gauss(array $matrix): array {
    $n = count($matrix);
    if ($n === 0 || count($matrix[0]) !== $n + 1) {
        throw new InvalidArgumentException("Матрица должна быть квадратной с добавленным столбцом свободных членов");
    }

    // Прямой ход
    for ($i = 0; $i < $n; $i++) {
        // Поиск опорного элемента
        $maxEl = abs($matrix[$i][$i]);
        $maxRow = $i;
        for ($k = $i + 1; $k < $n; $k++) {
            if (abs($matrix[$k][$i]) > $maxEl) {
                $maxEl = abs($matrix[$k][$i]);
                $maxRow = $k;
            }
        }

        // Перестановка строк, если нужно
        if ($maxRow !== $i) {
            $temp = $matrix[$i];
            $matrix[$i] = $matrix[$maxRow];
            $matrix[$maxRow] = $temp;
        }

        // Приведение к треугольному виду
        for ($k = $i + 1; $k < $n; $k++) {
            $c = -$matrix[$k][$i] / $matrix[$i][$i];
            for ($j = $i; $j < $n + 1; $j++) {
                if ($i === $j) {
                    $matrix[$k][$j] = 0;
                } else {
                    $matrix[$k][$j] += $c * $matrix[$i][$j];
                }
            }
        }
    }

    // Обратный ход
    $x = array_fill(0, $n, 0);
    for ($i = $n - 1; $i >= 0; $i--) {
        $x[$i] = $matrix[$i][$n] / $matrix[$i][$i];
        for ($k = $i - 1; $k >= 0; $k--) {
            $matrix[$k][$n] -= $matrix[$k][$i] * $x[$i];
        }
    }

    return $x;
} 