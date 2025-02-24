<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Вычисляет определённый интеграл методом левых прямоугольников
 */
function leftRectangle(callable $f, float $a, float $b, int $n): float {
    validateInputs($a, $b, $n);
    $h = ($b - $a) / $n;
    $sum = 0;
    // Берем точки с 0 до n-1 (левые концы отрезков)
    for ($i = 0; $i < $n; $i++) {
        $x = $a + $i * $h;  // Вычисляем x в левой точке отрезка
        $sum += $f($x);     // Используем значение функции в левой точке
    }
    return $h * $sum;
}

/**
 * Вычисляет определённый интеграл методом правых прямоугольников
 */
function rightRectangle(callable $f, float $a, float $b, int $n): float {
    validateInputs($a, $b, $n);
    $h = ($b - $a) / $n;
    $sum = 0;
    // Берем точки с 1 до n (правые концы отрезков)
    for ($i = 1; $i <= $n; $i++) {
        $x = $a + $i * $h;  // Вычисляем x в правой точке отрезка
        $sum += $f($x);     // Используем значение функции в правой точке
    }
    return $h * $sum;
}

/**
 * Вычисляет определённый интеграл методом трапеций
 */
function trapezoid(callable $f, float $a, float $b, int $n): float {
    validateInputs($a, $b, $n);
    $h = ($b - $a) / $n;
    // Берем полусумму значений на концах для первой и последней трапеции
    $sum = ($f($a) + $f($b)) / 2;
    
    // Суммируем значения во всех промежуточных точках
    // Каждая внутренняя точка участвует в формировании двух трапеций,
    // поэтому берется с коэффициентом 1, а не 1/2
    for ($i = 1; $i < $n; $i++) {
        $x = $a + $i * $h;
        $sum += $f($x);
    }
    return $h * $sum;
}

/**
 * Проверяет корректность входных данных для методов интегрирования
 */
function validateInputs(float $a, float $b, int $n): void {
    if ($n <= 0) {
        throw new InvalidArgumentException("Количество разбиений должно быть положительным");
    }
    if ($a >= $b) {
        throw new InvalidArgumentException("Верхний предел должен быть больше нижнего");
    }
} 