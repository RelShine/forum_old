<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: /index.php");
    if ($_SESSION['login'] !== 'Babinov') {
        header("Location: /index.php");
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Решение</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
// 21 15 9

if (!is_null($_GET['message'])) {
    $message = $_GET['message'];
    $keyEnc = explode(',', $_GET['key-enc']);
    $keyDec = explode(',', $_GET['key-dec']);
    $keyEnc = array_unique($keyEnc);
    $keyDec = array_unique($keyDec);
    $alphabet = [
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'q',
        'r',
        's',
        't',
        'u',
        'v',
        'w',
        'x',
        'y',
        'z',
        ' '
    ];

    echo 'C ';
    foreach ($keyEnc as $letter) {
        echo $letter . ' ';
    }
    echo '<br>';

    echo 'Nc ';
    foreach ($keyEnc as $letter) {
        for ($i = 0; $i < count($alphabet); ++$i) {
            if ($letter === $alphabet[$i]) {
                echo $i . ' ';
                $ncArr[] = $i;
            }
        }
    }
    echo '<br>';

    echo 'Np ';
    foreach ($keyDec as $letter) {
        for ($i = 0; $i < count($alphabet); ++$i) {
            if ($letter === $alphabet[$i]) {
                echo $i . ' ';
                $npArr[] = $i;
            }
        }
    }
    echo '<br>';

    echo 'P ';
    foreach ($keyDec as $letter) {
        echo $letter . ' ';
    }
    echo '<br>';

    $i = 0;
    foreach ($keyDec as $letter) {
        echo $letter . ' = a\'' . $ncArr[$i] . ' + ' . 'b\' (mod ' . count($alphabet) . ')';
        ++$i;
        echo '<br>';
    }

    die();
}

$a = $_GET['a'];
$b = $_GET['b'];
$m = $_GET['m'];
echo $a . 'x ' . ' === ' . $b . ' (mod ' . $m . ')<br>';

$d = (int)gmp_gcd($a, $m);
if ($d === 1) {
    echo '(a, m) = 1 => одно решение<br>';
} else {
    if ($d === 0) {
        echo '(a, m) = 0 => решений нет';
        exit();
    } else {
        if ($b % $d === 0) {
            echo '(a, m) = ' . $d . ' => решений ' . $d . '<br>';
        } else {
            echo 'b = ' . $b . ' не делится на ' . $d . '<br>Ответ: решений нет';
            exit();
        }
    }
}
$a1 = $a / $d;
$b1 = $b / $d;
$m1 = $m / $d;
echo $a1 . 'x ' . ' === ' . $b1 . ' (mod ' . $m1 . ')' . '<br>';

$i = 0;

while ($i < PHP_INT_MAX) {
    if ($a1 % ($b1 + $i * $m1) === 0 || ($b1 + $i * $m1) % $a1 === 0) {
        $hz = $i;
        break;
    }
    $hz = $i;
    echo $a1 . 'x ' . ' === ' . $b1 . ' + ' . $m1 . ' * ' . $hz . ' (mod ' . $m1 . ') - <br>';
    ++$i;
}

//if ((int)gmp_gcd($a1, $m1) === 1) {
echo '(' . $a1 . ';' . $m1 . ') = 1<br>';
//} else {
//    echo 'я не знаю что делать в этой ситуации поэтому die()';
//    die();
//}

echo $a1 . 'x ' . ' === ' . $b1 . ' + ' . $m1 . ' * ' . $hz . ' (mod ' . $m1 . ') + <br>';

$b2 = $b1 + $m1 * $hz;
echo $a1 . 'x ' . ' === ' . $b2 . ' (mod ' . $m1 . ')<br>';

$x0 = $b2 / $a1;
echo 'x === ' . $x0 . ' (mod ' . $m1 . ')<br>';

echo 'x0 = ' . $x0 . '<br>';
echo 'm1 = ' . $m1 . '<br>';

echo 'x === ' . $x0 . ' + ' . $m1 . ' * k (mod ' . $m . ')<br>';

$k = $d - 1;
echo 'k = (0;' . $k . ')<br>';

$arr = [];
for ($i = 0; $i <= $k; ++$i) {
    $arr[] = $x0 + $m1 * $i;
    echo 'k = ' . $i . ' =>' . ' x = ' . ($x0 + $m1 * $i) . '(mod ' . $m . ')<br>';
}
?>
</body>
</html>