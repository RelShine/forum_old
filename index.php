<?php
session_start();
require_once __DIR__ . '/templates/header.php';
$dbh = require_once __DIR__ . '/core/connection.php';
if (isset($_SESSION['auth'])) {
    $login = $_SESSION['login'];
    $queryExist = $dbh->prepare('SELECT COUNT(`login`) FROM `users` WHERE `login` = :login');
    $queryExist->execute(['login' => $login]);
    $arrExist = $queryExist->fetchColumn();
    if (count($arrExist) > 0) {
        $_SESSION['index'] = 'yes';
    } else {
        session_destroy();
    }
//    $queryBan = $dbh->query('SELECT `login`, `banned` FROM `users` WHERE `banned` = 1");
//
//    $ban_arr = mysqli_fetch_all($query_ban);
//    foreach ($ban_arr as $ban_user) {
//        if ($ban_user[0] === $login) {
//            exit('Вы были заблокированы');
//        }
//    }
}
?>
    <title>Форум Почты России - Главная страница</title>
    </head>
    <body>
<div class="preloader">
    <div class="preloader__row">
        <div class="preloader__item"></div>
        <div class="preloader__item"></div>
    </div>
</div>
<header class="header">
    <div class="container">
        <div class="header__wrapper">
            <div class="header__logo">
                <a class="header__logo-link" href="/index.php">
                    <img class="header__logo-img" src="/images/logo.png" alt="Лого">
                </a>
            </div>
            <div class="header__links">
                <?php
                if (isset($_SESSION['auth'])) {
                    echo ' < a class="header__links link-profile" href = "/pages/profile.php" > Личный кабинет </a > ';
                    echo '<a class="header__links link-logout" href = "/core/logout.php" > Выйти</a > ';
                    if ($_SESSION['login'] === 'Babinov') {
                        echo ' < a class="header__links link-calc" href = "/calc/index.php" style = "color: #ff0000;" > Калькулятор</a > ';
                    }
                } else {
                    echo '<a class="header__links link-auth" data-fancybox href = "#login" > Авторизация</a > ';
                    echo '<a class="header__links link-register" href="/pages/register.php" > Регистрация</a > ';
                }
                ?>
            </div>
        </div>
    </div>
</header>
<main class="main">
    <section class="path">
        <div class="container">
            <div class="path__inner">
                <span class="path__text-active">Главная</span>
                <span class="path__text-active-help">&lt;-- вы здесь</span>
            </div>
        </div>
    </section>
    <section class="chapters">
        <div class="container">
            <div class="chapters__headers">
                <h3 class="chapters__header">Разделы</h3>
            </div>
            <?php
            $query = mysqli_query($connection, "SELECT * FROM chapters");
            $id_query = mysqli_query($connection, "SELECT id FROM chapters");
            $chapters = mysqli_fetch_all($query);
            $id_chapter = mysqli_fetch_all($id_query);
            $id_topic = 0;
            foreach ($chapters as $chapter) {
                echo '<div class="chapters__chapter" > ' .
                    '<a class="chapter__title" href = "/pages/topics.php?id=' . $id_chapter[$id_topic][0] . '" > ' . $chapter[1] . '</a > ' .
                    '</div > ';
                ++$id_topic;
            }
            if (isset($_SESSION['login'])) {
                $login_user = $_SESSION['login'];
                $query2 = mysqli_query($connection, "SELECT role FROM users WHERE role = 3 AND login = '$login_user'");
                if (mysqli_num_rows($query2) > 0) {
                    echo <<<'NOWDOC'
                    <p class="chapters__admin-text">
                        <a class="chapters__admin-link" data-fancybox href="#chapter__create">
                            <img class="chapters__admin-image" src="/images/add-icon.svg" alt="Добавить">
                        </a>
                        <span class="chapters__admin-info-pc">АДМИН ДОСТУП</span>
                    </p>
                    <span class="chapters__admin-info-mobile">АДМИН ДОСТУП</span>
                    NOWDOC;
                }
            }
            ?>
        </div>
    </section>
</main>
<?php
require_once __DIR__ . '/templates/footer.php';
?>