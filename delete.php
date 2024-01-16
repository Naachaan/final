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
    <br><hr><br>
    <div class="music-list">
    <?php
    // 削除後の楽曲リスト表示
    foreach ($pdo->query('SELECT * FROM music') as $row) {
        echo '<div class="song">';
        echo '<img class="img" alt="image" src="image/', $row['id'], '.png">';
        echo '<p>',$row['title'],'</p>';
        echo '<p>',$row['artist'],'</p>';
        echo '</div>';
    }
    ?> 
    </div>
    <form action="musiclist.php" method="post">
        <button type="submit">削除画面へ戻る</button>
    </form>
</body>
</html>
