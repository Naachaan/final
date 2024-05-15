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