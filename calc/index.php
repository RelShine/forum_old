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
    <title>Калькулятор</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="solution__one">
    <form action="solution.php" method="get">
        <div class="value__wrapper">
            <span>a:</span>
            <input class="value__a" type="text" id="value_a" name="a">
        </div>
        <div class="value__wrapper">
            <span>b:</span>
            <input class="value__b" type="text" id="value_b" name="b">
        </div>
        <div class="value__wrapper">
        <span>m:<span>
        <input class="value__m" type="text" id="value_m" name="m">
        </div>
        <button class="btn" type="submit">Расчет</button>
    </form>
</div>
<!--<div class="solution__two">
    <form action="solution.php" method="get">
        <div class="value__wrapper">
            <span>mes:</span>
            <input class="value__message" type="text" id="value_message" name="message">
        </div>
        <div class="value__wrapper">
            <span>enc:</span>
            <input class="value__key-enc" type="text" id="value_key-enc" name="key-enc">
        </div>
        <div class="value__wrapper">
            <span>dec:</span>
            <input class="value__key-dec" type="text" id="value_key-dec" name="key-dec">
        </div>
        <button class="btn" type="submit">Расчет</button>
    </form>
</div>-->
</body>
</html>