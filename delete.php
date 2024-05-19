<?php
require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);

// 削除処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $sql = $pdo->prepare('DELETE FROM music WHERE id = ?');
    if ($sql->execute([$_POST['id']])) {
        echo '削除に成功しました。';
    } else {
        echo '削除に失敗しました。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <link rel="stylesheet" href="./css/style.css" />
    <meta charset="UTF-8">
    <title>削除</title>
</head>
<body>
    <h1><a href="musiclist.php">Playlist</a></h1>
    <hr>
    <div class="music-list">
    <?php
    // 削除後の楽曲リスト表示
    foreach ($pdo->query('SELECT * FROM music') as $row) {
        echo '<div class="song">';
        echo '<img class="img" alt="image" src="', htmlspecialchars($row['image']), '">';
        echo '<p class="ctgr">', htmlspecialchars($row['category']), '</p>';
        echo '<p class="title">', htmlspecialchars($row['title']), ' - ', htmlspecialchars($row['artist']), '</p>';
        echo '<div class="botton">';
        echo '<input type="hidden" name="id" value="', htmlspecialchars($row['id']), '">';
        echo '<form action="delete.php" method="post">';
        echo '<input type="hidden" name="id" value="', htmlspecialchars($row['id']), '">';
        echo '<button type="submit">削除</button>';
        echo '</form></div></div>';
    }
    ?> 
    </div>
    <form action="musiclist.php" method="post">
        <button type="submit">トップ画面へ戻る</button>
    </form>
</body>
</html>
