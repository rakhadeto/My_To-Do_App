<?php
require_once __DIR__ . '/todo-app/config/database.php';
require_once __DIR__ . '/classes/TodoManager.php';

$database = new Database();
$db_connection = $database->connect();

if ($db_connection === null) {
    die("Aplikasi tidak dapat terhubung ke database. Mohon periksa konfigurasi database Anda dan status server MySQL.");
}

$todoManager = new TodoManager($db_connection);
$todos = $todoManager->getTodos();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@300;400;600;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'database/includes/header.php'; ?>

    <div class="container">
        <h2>My To-Do List</h2>

        <form method="post" action="todo-app/config/index.php">
            <input type="text" name="task" placeholder="Add new task" required>
            <input type="hidden" name="action" value="add">
            <button type="submit">Add Task</button>
        </form>

        <ul class="todo-list">
            <?php foreach ($todos as $todo): ?>
                <li class="<?= $todo['completed'] ? 'completed' : '' ?>" data-id="<?= $todo['id'] ?>">
                    <button type="button" class="toggle-button" style="background:none;border:none;">
                        <?= $todo['completed'] ? '✅' : '⬜' ?>
                    </button>
                    <span><?= htmlspecialchars($todo['task']) ?></span> </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php include 'database/includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const todoList = document.querySelector('.todo-list');

            todoList.addEventListener('click', function(event) {
                const listItem = event.target.closest('li[data-id]'); // Dapatkan li terdekat dengan data-id

                if (listItem) {
                    const taskId = listItem.dataset.id; // Ambil ID tugas
                    
                    // Toggle class 'completed' untuk perubahan warna langsung
                    listItem.classList.toggle('completed');

                    // Kirim permintaan ke server untuk mengubah status (menggunakan Fetch API)
                    fetch('todo-app/config/index.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=toggle&id=${taskId}`
                    })
                    .then(response => {
                        // Jika respons OK, update emoji tombol
                        if (response.ok) {
                            const toggleButton = listItem.querySelector('.toggle-button');
                            if (listItem.classList.contains('completed')) {
                                toggleButton.textContent = '✅';
                            } else {
                                toggleButton.textContent = '⬜';
                            }
                        } else {
                            // Jika ada error di server, kembalikan class 'completed'
                            listItem.classList.toggle('completed');
                            console.error('Failed to toggle task on server');
                        }
                    })
                    .catch(error => {
                        // Jika ada masalah jaringan, kembalikan class 'completed'
                        listItem.classList.toggle('completed');
                        console.error('Network error:', error);
                    });
                }
            });
        });
    </script>
</body>
</html>