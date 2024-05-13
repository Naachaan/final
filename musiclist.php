<?php require 'db-connect.php';
// データベース接続
$pdo = new PDO($connect, USER, PASS);
define( "FILE_DIR", "images/test/");
$clean = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['dasis']) && $_POST['dasis'] == 'edit'){
        // データの更新処理
        $sql = $pdo->prepare('UPDATE music SET title=?, artist=?, category=? WHERE id=?');
        
        if (empty($_POST['title'])) {
            echo 'update song title';
        } else if (empty($_POST['artist'])) {
            echo 'update artist name';
        }else if(empty($_POST['category'])){
            echo 'update ganre';
        } else if ($sql->execute([htmlspecialchars($_POST['title']), htmlspecialchars($_POST['artist']),htmlspecialchars($_POST['category']), $_POST['id']])) {
            // 更新が成功した場合のみメッセージを表示
            echo 'Update was successful';
            unset($_POST['dasis']);
        } else {
            echo 'Update failed';
        }
    }else if(isset($_POST['dasis']) && $_POST['dasis'] == 'insert'){
        $sql=$pdo->prepare('insert into music(title,artist,category,image) values(?,?,?,?)');
        if (empty($_FILES['file']['name'])) {
            $fileName='noimages.png';
        } else {
            // 画像のアップロード処理
            $targetDir = "../image/";
            $fileName = basename($_FILES["file"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        }
        if(empty($_POST['title'])){
            echo 'add song title';
        }else if(empty($_POST['artist'])){
            echo 'add artist name';
        }else if(empty($_POST['category'])){
            echo 'add ganre';
        } else if ($sql->execute([htmlspecialchars($_POST['title']),$_POST['artist'],$_POST['category'],$fileName])) {
            // 更新が成功した場合のみメッセージを表示
            echo 'Added song';
            unset($_POST['dasis']);
        } else {
            echo 'Failed to add song';
        }
    }
} else {
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Cache-Control" content="no-cache">
    <link rel="stylesheet" href="css/style.css" />
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
            echo '<img src="image/',$row['image'],'">';
            echo '<p class="ctgr">',$row['category'],'</p>';
            echo '<p class="title">',$row['title'],' - ',$row['artist'],'</p>';
            echo '<div class="botton">';
            echo '<input type="hidden" name="id" value="', $row['id'], '">';
            echo '<button id="edit" class="edit" onclick="openModal(' . $row['id'] . ', \'' . $row['title'] . '\', \'' . $row['artist'] . '\', \'' . $row['category'] . '\')">更新</button>';
            echo '<form action="delete.php" method="post">';
            echo '<input type="hidden" name="id" value="', $row['id'], '">';
            echo '<button type="submit">削除</button>';
            echo '</form></div></div>';
        }
        require 'edit.php';
        ?>
    </div>
    <form action="musiclist.php" method="post">
        <div class="insert">
        <label>Album image:</label>
		<input type="file" name="image">
        <label for="name">Title:</label>
        <input type="text" name="title" placeholder="Enter the title of the song" required>
        <label for="artist">   Artist:</label>
        <input type="text" name="artist" placeholder="Enter the artist name" required>
        <label for="category">Ganre:</label>
        <input type="text" name="category" list="ganre" placeholder="Text input or selection" autocomplete="on" require>
        <datalist id="ganre">
            <?php
            foreach ($pdo->query('SELECT DISTINCT category FROM music') as $categoryrow) {
                $category = htmlspecialchars($categoryrow['category']);
                echo '<option value="' , $category , '">' , $category , '</option>';
            }
            if (empty($_FILES['file']['name'])) {
                    echo '画像ファイルを選択してください。';
                } else {
                    // 画像のアップロード処理
                    $targetDir = "../image/";
                    $fileName = basename($_FILES["file"]["name"]);
                    $targetFilePath = $targetDir . $fileName;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                        // データベースに画像情報を保存
                        $productSql = $pdo->prepare('INSERT INTO game (game_id, game_name, image_pass) VALUES (?, ?, ?)');
                        if ($productSql->execute([$_POST['id'], $_POST['name'], $fileName])) {
                            echo 'ゲームの追加に成功しました。';
                        } else {
                            echo 'データベースへの登録に失敗しました。';
                        }
                    } else {
                        echo 'ファイルのアップロードに失敗しました。';
                    }
                }
            ?>
        </datalist>
        </div>
        <button type="submit"><input type="hidden" name="dasis" value="insert">追加</button>
    </form>
</body>
</html>
<script>
    function openModal(id, title, artist, category) {
        document.getElementById('editForm').style.display = 'block';
        document.getElementById('editTitle').value = title;
        document.getElementById('editArtist').value = artist;
        document.getElementById('editCategory').value = category;
        document.getElementById('editId').value = id;
    }

    function closeModal() {
        document.getElementById('editForm').style.display = 'none';
    }
</script>