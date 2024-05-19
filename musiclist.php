<?php
require 'db-connect.php';

// データベース接続
$pdo = new PDO($connect, USER, PASS);

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <title>MusicPlaylist</title>
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
    <header class="header">
        <h1 
        style="margin-top:0; font-size: 50px; font-family: 'Caveat', cursive; font-optical-sizing: auto; font-weight: <weight>; font-style: normal;">
        Music Play List</h1>
    </header>
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
            echo '<button id="edit" class="edit" onclick="openModal(' . htmlspecialchars($row['id']) 
            . ', \'' . htmlspecialchars($row['title']) . '\', \'' . htmlspecialchars($row['artist']) 
            . '\', \'' . htmlspecialchars($row['category']) . '\')">変更</button>';
            echo '<form action="delete.php" method="post">';
            echo '<input type="hidden" name="id" value="', htmlspecialchars($row['id']), '">';
            echo '<button type="submit" class="delete">削除</button>';
            echo '</form></div></div>';
        }
        require 'edit.php';
        ?>
        <button class="plus" onclick="openInsertModal()">+</button>
    </div>
    <?php require 'insert.php';?>

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
        } else if (event.target == document.getElementById('editForm')) {
            document.getElementById('editForm').style.display = 'none';
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
