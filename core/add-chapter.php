<?php
session_start();
$connection = require_once 'connection.php';

$chapter_title = $_POST['chapter_title'];

$chapter_title_spec = mysqli_real_escape_string($connection, $chapter_title);
if ($chapter_title_spec !== $chapter_title) {
    echo 'chapter-incorrect-slash';
    exit();
}

$chapter_title_spec_html = htmlspecialchars($chapter_title_spec);
if ($chapter_title_spec_html !== $chapter_title_spec) {
    echo 'chapter-incorrect-html';
    exit();
}

$query = mysqli_query($connection, "SELECT * FROM chapters WHERE chapter = '$chapter_title_spec_html'");
if (mysqli_num_rows($query) > 0) {
    echo 'chapter-exist';
    exit();
}

$query = mysqli_query($connection, "INSERT INTO chapters VALUES (NULL, '$chapter_title')");

echo 1;
exit();