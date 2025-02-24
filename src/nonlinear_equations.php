<?php
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Решает нелинейное уравнение методом хорд
 */
function chord(callable $f, float $a, float $b, float $epsilon = 1e-10, int $maxIterations = 100): float {
    $fa = $f($a);
    $fb = $f($b);

    if ($fa * $fb > 0) {
        throw new RuntimeException("На концах интервала функция должна иметь разные знаки.");
    }

    for ($i = 0; $i < $maxIterations; $i++) {
        $x = $a - $fa * ($b - $a) / ($fb - $fa);
        $fx = $f($x);

        if (abs($fx) < $epsilon) {
            return $x;
        }

        if ($fa * $fx < 0) {
            $b = $x;
            $fb = $fx;
        } else {
            $a = $x;
            $fa = $fx;
        }
    }

    throw new RuntimeException("Метод хорд не сошелся за $maxIterations итераций.");
}

/**
 * Решает нелинейное уравнение методом Ньютона
 */
function newton(callable $f, callable $df, float $x0, float $epsilon = 1e-10, int $maxIterations = 100): float {
    $x = $x0;
    for ($i = 0; $i < $maxIterations; $i++) {
        $fx = $f($x);
        $dfx = $df($x);

        if ($dfx === 0.0) {
            throw new RuntimeException("Производная равна нулю. Метод Ньютона не применим.");
        }

        $xNext = $x - $fx / $dfx;

        if (abs($xNext - $x) < $epsilon) {
            return $xNext;
        }

        $x = $xNext;
    }

    throw new RuntimeException("Метод Ньютона не сошелся за $maxIterations итераций.");
}
