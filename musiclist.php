<?php require 'db-connect.php';
// データベース接続
$pdo = new PDO($connect, USER, PASS);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['dasis']) && $_POST['dasis'] == 'edit'){
        // データの更新処理
        $sql = $pdo->prepare('UPDATE music SET title=?, artist=? WHERE id=?');
        
        if (empty($_POST['title'])) {
            echo 'update song title';
        } else if (empty($_POST['artist'])) {
            echo 'update artist name';
        } else if ($sql->execute([htmlspecialchars($_POST['title']), htmlspecialchars($_POST['artist']), $_POST['id']])) {
            // 更新が成功した場合のみメッセージを表示
            echo 'Success';
            unset($_POST['dasis']);
        } else {
            echo 'Failure';
        }
    }else if(isset($_POST['dasis']) && $_POST['dasis'] == 'insert'){
        $sql=$pdo->prepare('insert into music(title,artist) values(?,?)');
        if(empty($_POST['title'])){
            echo 'add song title';
        }else if(empty($_POST['artist'])){
            echo 'aqdd artist name';
        } else if ($sql->execute([htmlspecialchars($_POST['title']),$_POST['artist']])) {
            // 更新が成功した場合のみメッセージを表示
            echo 'Success';
            unset($_POST['dasis']);
        } else {
            echo 'Failure';
        }
    }
} else {
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Cache-Control" content="no-cache">
    <link rel="stylesheet" href="./css/style.css" />
    <meta charset="UTF-8">
    <title>Playlist</title>
</head>
<body>
    <h1>Playlist</h1>
    <hr>
    <div class="music-list">
        <?php
        // 楽曲一覧表示
        foreach ($pdo->query('SELECT * FROM music') as $row) {
            echo '<div class="song">';
            echo '<img class="img" alt="image" src="image/', htmlspecialchars($row['id']), '.png">';
            echo $row['title'],'<br>';
            echo $row['artist'];
            echo '<form action="edit.php" method="post">';
            echo '<input type="hidden" name="id" value="', $row['id'], '">';
            echo '<button type="submit">更新</button>';
            echo '</form>';
            echo '<form action="delete.php" method="post">';
            echo '<input type="hidden" name="id" value="', $row['id'], '">';
            echo '<button type="submit">削除</button>';
            echo '</form></div>';
        }
        ?>
    </div>
    <form action="musiclist.php" method="post">
        <label for="name">Song title:</label>
        <input type="text" name="title" placeholder="Enter the title of the song" required><br>
        <label for="artist">   Artist:</label>
        <input type="text" name="artist" placeholder="Enter the artist name" required><br>
        <button type="submit"><input type="hidden" name="dasis" value="insert">追加</button>
    </form>
</body>
</html>
