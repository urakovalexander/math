<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/numerical_integration.php';
require_once __DIR__ . '/nonlinear_equations.php';
require_once __DIR__ . '/linear_equations.php';
use NXP\MathExecutor;

/**
 * Преобразует строку с математическим выражением в callable функцию
 *
 * @param string $funcStr Математическое выражение
 * @return callable
 */
function parseFunction(string $funcStr): callable
{
    $executor = new MathExecutor();

    return function ($x) use ($funcStr, $executor) {
        $executor->setVar('x', $x);

        return $executor->execute($funcStr);
    };
}

echo "Выберите метод решения:\n";
echo "1. leftRectangle (левые прямоугольники)\n";
echo "2. rightRectangle (правые прямоугольники)\n";
echo "3. trapezoid (трапеции)\n";
echo "4. gauss (Гаусс)\n";
echo "5. chord (хорд)\n";
echo "6. newton (Ньютон)\n";
$method = trim(fgets(STDIN));

$validMethods = ['1', '2', '3', '4', '5', '6'];
if (!in_array($method, $validMethods)) {
    echo "Некорректный метод." . PHP_EOL;
    exit;
}

switch ($method) {
    case '1':
    case '2':
    case '3':
        echo "Введите функцию f(x) (например, x^2):\n";
        $funcStr = trim(fgets(STDIN));
        $f = parseFunction($funcStr);

        echo "Введите нижний предел a: ";
        $a = (float)trim(fgets(STDIN));

        echo "Введите верхний предел b: ";
        $b = (float)trim(fgets(STDIN));

        echo "Введите количество разбиений n: ";
        $n = (int)trim(fgets(STDIN));

        $solution = null;
        try {
            switch ($method) {
                case '1':
                    $solution = leftRectangle($f, $a, $b, $n);
                    break;
                case '2':
                    $solution = rightRectangle($f, $a, $b, $n);
                    break;
                case '3':
                    $solution = trapezoid($f, $a, $b, $n);
                    break;
            }
            if ($solution !== null) {
                echo "Решение: " . $solution . PHP_EOL;
            }
        } catch (InvalidArgumentException $e) {
            echo "Ошибка: " . $e->getMessage() . PHP_EOL;
        }
        break;

    case '4':
        echo "Введите матрицу (строка за строкой, через запятую, пример: 3,4,5,12):\n";
        echo "Для завершения ввода нажмите Enter на пустой строке.\n";
        $matrix = [];
        while (true) {
            $rowStr = trim(fgets(STDIN));
            if (empty($rowStr)) {
                break;
            }
            $row = array_map('floatval', explode(',', $rowStr));
            $matrix[] = $row;
        }

        $solution = null;
        try {
            $solution = gauss($matrix);
            if ($solution !== null) {
                echo "Решение: " . implode(', ', $solution) . PHP_EOL;
            }
        } catch (InvalidArgumentException $e) {
            echo "Ошибка: " . $e->getMessage() . PHP_EOL;
        }
        break;

    case '5': // chord
        echo "Введите функцию f(x) (например, x^2 - 16):\n";
        $funcStr = trim(fgets(STDIN));
        $f = parseFunction($funcStr);

        echo "Введите левую границу интервала a: ";
        $a = (float)trim(fgets(STDIN));

        echo "Введите правую границу интервала b: ";
        $b = (float)trim(fgets(STDIN));

        $solution = null;
        try {
            $solution = chord($f, $a, $b);
            if ($solution !== null) {
                echo "Решение: " . $solution . PHP_EOL;
            }
        } catch (RuntimeException $e) {
            echo "Ошибка: " . $e->getMessage() . PHP_EOL;
        }
        break;

    case '6': // newton
        echo "Введите функцию f(x) (например, x^2 - 4):\n";
        $funcStr = trim(fgets(STDIN));
        $f = parseFunction($funcStr);

        echo "Введите производную f'(x) (например, 2*x):\n";
        $dfuncStr = trim(fgets(STDIN));
        $df = parseFunction($dfuncStr);

        echo "Введите начальное приближение x0: ";
        $x0 = (float)trim(fgets(STDIN));

        $solution = null;
        try {
            $solution = newton($f, $df, $x0);
            if ($solution !== null) {
                echo "Решение: " . $solution . PHP_EOL;
            }
        } catch (RuntimeException $e) {
            echo "Ошибка: " . $e->getMessage() . PHP_EOL;
        }
        break;

    default:
        echo "Некорректный метод." . PHP_EOL;
}
