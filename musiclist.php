<?php
require 'db-connect.php';

// データベース接続
$pdo = new PDO($connect, USER, PASS);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['dasis']) && $_POST['dasis'] == 'edit') {
        // データの更新処理
        $sql = $pdo->prepare('UPDATE music SET title=?, artist=?, category=? WHERE id=?');
        
        if (empty($_POST['title'])) {
            echo 'update song title';
        } elseif (empty($_POST['artist'])) {
            echo 'update artist name';
        } elseif (empty($_POST['category'])) {
            echo 'update genre';
        } elseif ($sql->execute([htmlspecialchars($_POST['title']), htmlspecialchars($_POST['artist']), htmlspecialchars($_POST['category']), $_POST['id']])) {
            // 更新が成功した場合のみメッセージを表示
            echo 'Update was successful';
            unset($_POST['dasis']);
        } else {
            echo 'Update failed';
        }
    } elseif (isset($_POST['dasis']) && $_POST['dasis'] == 'insert') {
        $sql = $pdo->prepare('INSERT INTO music (title, artist, category, image) VALUES (?, ?, ?, ?)');
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            if (!file_exists('image')) {
                if (!mkdir('image')) {
                    die('Failed to create directory.');
                }
            }
            $file = './image/' . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
                echo 'File uploaded successfully.';
            } else {
                die('Failed to move uploaded file.');
            }
        } else {
            $file = 'image/noimages.png';
        }
        if (empty($_POST['title'])) {
            echo 'add song title';
        } elseif (empty($_POST['artist'])) {
            echo 'add artist name';
        } elseif (empty($_POST['category'])) {
            echo 'add genre';
        } elseif ($sql->execute([htmlspecialchars($_POST['title']), htmlspecialchars($_POST['artist']), htmlspecialchars($_POST['category']), $file])) {
            // 追加が成功した場合のみメッセージを表示
            echo 'Added song';
            unset($_POST['dasis']);
        } else {
            echo 'Failed to add song';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Cache-Control" content="no-cache">
    <link rel="stylesheet" href="css/style.css" />
    <meta charset="UTF-8">
    <title>Playlist</title>
    <style>
        .insert {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .insert-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Playlist</h1>
    <hr>
    <div class="music-list">
        <?php
        // 楽曲一覧表示
        foreach ($pdo->query('SELECT * FROM music') as $row) {
            echo '<div class="song">';
            echo '<img class="img" alt="image" src="', htmlspecialchars($row['image']), '">';
            echo '<p class="ctgr">', htmlspecialchars($row['category']), '</p>';
            echo '<p class="title">', htmlspecialchars($row['title']), ' - ', htmlspecialchars($row['artist']), '</p>';
            echo '<div class="botton">';
            echo '<input type="hidden" name="id" value="', htmlspecialchars($row['id']), '">';
            echo '<button id="edit" class="edit" onclick="openModal(' . htmlspecialchars($row['id']) . ', \'' . htmlspecialchars($row['title']) . '\', \'' . htmlspecialchars($row['artist']) . '\', \'' . htmlspecialchars($row['category']) . '\')">更新</button>';
            echo '<form action="delete.php" method="post">';
            echo '<input type="hidden" name="id" value="', htmlspecialchars($row['id']), '">';
            echo '<button type="submit">削除</button>';
            echo '</form></div></div>';
        }
        require 'edit.php';
        ?>
    </div>

    <button onclick="openInsertModal()">楽曲を追加</button>

    <div id="insertModal" class="insert">
        <div class="insert-content">
            <span class="close" onclick="closeInsertModal()">&times;</span>
            <form action="musiclist.php" method="post" enctype="multipart/form-data">
                <label>Album image:</label>
                <input type="file" name="file"><br>
                <label for="name">Title:</label>
                <input type="text" name="title" placeholder="Enter the title of the song" required><br>
                <label for="artist">Artist:</label>
                <input type="text" name="artist" placeholder="Enter the artist name" required><br>
                <label for="category">Genre:</label>
                <input type="text" name="category" list="genre" placeholder="Text input or selection" autocomplete="on" required><br>
                <datalist id="genre">
                    <?php
                    foreach ($pdo->query('SELECT DISTINCT category FROM music') as $categoryrow) {
                        $category = htmlspecialchars($categoryrow['category']);
                        echo '<option value="' , $category , '">' , $category , '</option>';
                    }
                    ?>
                </datalist>
                <input type="hidden" name="dasis" value="insert">
                <button type="submit">追加</button>
            </form>
        </div>
    </div>

</body>
</html>
<script>
    function openInsertModal() {
        document.getElementById('insertModal').style.display = 'block';
    }

    function closeInsertModal() {
        document.getElementById('insertModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('insertModal')) {
            document.getElementById('insertModal').style.display = 'none';
        }
    }

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
