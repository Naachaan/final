<div id="editForm" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <form action="musiclist.php" method="post">
                    <label for="editTitle">Title:</label>
                    <input type="text" name="title" id="editTitle" required><br>
                    <label for="editArtist">Artist:</label>
                    <input type="text" name="artist" id="editArtist" required><br>
                    <label for="editCategory">Genre:</label>
                    <input type="text" name="category" list="ganre" id="editCategory" autocomplete="on" required>
                    <datalist id="ganre">
                        <?php
                        foreach ($pdo->query('SELECT DISTINCT category FROM music') as $categoryrow) {
                            $category = htmlspecialchars($categoryrow['category']);
                            echo '<option value="' , $category , '">' , $category , '</option>';
                        }
                        ?>
                    </datalist>
                    <input type="hidden" name="dasis" value="edit">
                    <input type="hidden" name="id" id="editId" value="">
                    <br><input type="submit" class="kosin" value="更新">
                </form>
            </div>
        </div>