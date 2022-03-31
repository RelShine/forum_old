<?php
session_start();
require_once __DIR__ . '/../templates/header.php';
unset($_SESSION['index']);
$connection = require_once __DIR__ . '/../core/connection.php';

$id = $_GET['id'];
$id_spec = mysqli_real_escape_string($connection, $id);
if ($id_spec !== $id) {
    exit("<meta http-equiv='refresh' content='0; url= /index.php'>");
}

$id_topic2 = $_GET['id'];
$id_topic2_spec = mysqli_real_escape_string($connection, $id_topic2);
if ($id_topic2_spec !== $id_topic2) {
    exit("<meta http-equiv='refresh' content='0; url= /index.php'>");
}

$query_id_chapter = mysqli_query($connection, "SELECT chapter FROM chapters WHERE id = '$id'");
if (mysqli_num_rows($query_id_chapter) < 1) {
    exit("<meta http-equiv='refresh' content='0; url= /index.php'>");
}
?>
    <title>Форум Почты России - Страница тем</title>
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
                    echo '<a class="header__links link-profile" href="/pages/profile.php">Личный кабинет</a>';
                    echo '<a class="header__links link-logout" href="/core/logout.php">Выйти</a>';
                } else {
                    echo '<a class="header__links link-auth" href="/index.php">Главная</a>';
                    echo '<a class="header__links link-register" href="/pages/register.php">Регистрация</a>';
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
                <a class="path__text" href="/index.php">Главная</a>
                <span class="path__separator">/</span>
                <?php
                $_SESSION['chapter0'] = $id;
                $id_chapter_arr = mysqli_fetch_assoc($query_id_chapter);
                echo '<span class="path__text-active">' . $id_chapter_arr['chapter'] . '</span>';
                ?>
                <span class="path__separator">/</span>
                <span class="path__text-active">Темы</span>
                <span class="path__text-active-help">&lt;-- вы здесь</span>
            </div>
        </div>
    </section>
    <section class="chapters">
        <div class="container">
            <div class="chapters__headers">
                <h3 class="chapters__header">Темы</h3>
                <h3 class="chapters__header chapters__header-last-message">Последнее сообщение</h3>
            </div>
            <?php
            $_SESSION['id_chapter'] = $id;
            $query = mysqli_query($connection, "SELECT * FROM topics WHERE id_chapter = '$id'");
            $id_query = mysqli_query($connection, "SELECT id FROM topics WHERE id_chapter = '$id'");
            $id_query2 = mysqli_query($connection, "SELECT id FROM topics WHERE id_chapter = '$id'");
            $id_query3 = mysqli_fetch_assoc($id_query2);
            if (isset($id_query3)) {
                $_SESSION['id_topic'] = $id_query3['id'];
            }
            $topics = mysqli_fetch_all($query);
            $id_topics = mysqli_fetch_all($id_query);
            $id_topic = 0;
            $query11 = mysqli_query($connection, "SELECT t.id FROM messages m, topics t, chapters c, users u WHERE t.id_chapter = c.id AND t.id_chapter = '$id_topic2' GROUP BY t.id");
            $query11_res = mysqli_fetch_all($query11);

            $arr = [];
            $index = 0;

            foreach ($query11_res as $test11 => $key) {
                $arr[$index] = $key[0];
                ++$index;
            }

            $arr2 = [];
            $index2 = 0;
            $query22_res = [0, 0, 0];
            foreach ($arr as $test22 => $key2) {
                $query22 = mysqli_query($connection, "SELECT m.id, m.message, m.id_topic FROM messages m, topics t, chapters c, users u WHERE m.id_user = u.id AND m.id_topic = t.id AND m.id_chapter = c.id AND m.id_chapter = '$id_topic2' AND m.id_chapter = t.id_chapter AND m.id_topic = '$arr[$index2]' ORDER BY m.id DESC LIMIT 1");
                $query22_res = mysqli_fetch_all($query22);
                $arr2[$index2] = $key2[0];
                ++$index2;
            }

            $index = 0;

            $query_test = mysqli_query($connection, "SELECT m.id FROM messages m, topics t, chapters c, users u WHERE m.id_user = u.id AND m.id_topic = t.id AND m.id_chapter = c.id AND m.id_chapter = '$id_topic2' ORDER BY m.id");
            $test_arr = mysqli_fetch_all($query_test);

            foreach ($topics as $topic) {
                $query_last_message = mysqli_query($connection, "SELECT u.login, u.avatar, m.message FROM users u, messages m, chapters c, topics t WHERE m.id_user = u.id AND t.id_chapter = '$id_topic2' AND m.id_topic = '$arr2[$id_topic]' AND t.id = m.id_topic AND c.id = t.id_chapter ORDER BY m.id DESC LIMIT 1");
                $last_message_arr = mysqli_fetch_assoc($query_last_message);
                $chet = 0;
                if (strlen($last_message_arr['message']) > 10) {
                    $last_message_arr['message'] = 'Читайте в теме';
                    ++$chet;
                }
                echo '<div class="chapters__chapter">' .
                    '<a class="chapter__title" href="/pages/topic.php?id=' . $id_topics[$id_topic][0] . '">' . $topic[1] . '</a>' .
                    '<div class="chapter__user">' .
                    '<div class="chapter__user-avatar-wrapper">' .
                    '<img src="data:image/jpeg;base64,' . base64_encode($last_message_arr['avatar']) . '" class="chapter__user-avatar" alt="Аватар" width="42" height="42">' .
                    '</div>' .
                    '<div class="chapter__user-wrapper">';
                if ($last_message_arr['login'] == 'Babinov') {
                    echo '<p class="chapter__user-nick" style="color: red; text-align: center;">' . $last_message_arr['login'] . '</p>' . '<p style="color: red; text-align: center; margin-top: -8px; font-size: 14px;">АДМИНИСТРАТОР</p>';
                } else {
                    echo '<p class="chapter__user-nick">' . $last_message_arr['login'] . '</p>';
                }
                echo
                    '<p class="chapter__user-nick">' . $last_message_arr['message'] . '</p>' .
                    '</div>' .
                    '</div>' .
                    '</div>';
                ++$id_topic;
            }
            ?>
            <div>
                <?php
                if (isset($_SESSION['auth'])) {
                    echo
                    '<p style="background-color: #183368; padding: 8px 0">
                    <a data-fancybox href="#create" style="display: flex; justify-content: center; align-items: center">
                        <img src="/images/add-icon.svg" alt="Добавить">
                    </a>
                </p>';
                } else {
                    echo
                    '<p style="background-color: #183368; padding: 8px 0">
                    <a style="display: flex; justify-content: center; align-items: center">
                        <img src="/images/add-icon.svg" alt="Добавить">
                    </a>
                </p>
                <p class="send__message-no-auth">Вы не вошли в систему и не можете добавлять темы. Зарегистрируйтесь или авторизируйтесь.</p>';
                }
                ?>
            </div>
        </div>
    </section>
</main>
<?php
require_once __DIR__ . '/../templates/footer.php';
?>