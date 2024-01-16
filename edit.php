<?php
require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = $pdo->prepare('SELECT * FROM music WHERE id=?');
    $sql->execute([$_POST['id']]);

    foreach ($sql as $row) {
        echo '<form action="musiclist.php" method="post">';
        echo '<input type="text" name="title" value="', $row['title'], '">';
        echo ' <input type="text" name="artist" value="', $row['artist'], '">';
        echo '<input type="hidden" name="dasis" value="edit">'; // 隠しフィールド
        echo '<input type="hidden" name="id" value="', $row['id'], '">';
        echo '<input type="submit" value="更新">';
        echo '</form>';
    }
}
?>

<button onclick="location.href='musiclist.php'">トップへ戻る</button>
