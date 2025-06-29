<?php
// Memanggil database.php yang berada di direktori yang sama
require_once __DIR__ . '/database.php';

// Jalur relatif ke TodoManager.php dari todo-app/config
require_once __DIR__ . '/../../classes/TodoManager.php';

// Buat instance koneksi database
$database = new Database();
$db_connection = $database->connect();

// Periksa apakah koneksi database berhasil
if ($db_connection === null) {
    die("Aplikasi tidak dapat terhubung ke database. Mohon periksa konfigurasi database Anda dan status server MySQL.");
}

// Buat instance TodoManager dengan koneksi PDO
$todoManager = new TodoManager($db_connection);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'add') {
        $task = $_POST['task'] ?? '';
        if (!empty($task)) {
            $todoManager->addTodo($task);
        }
    } elseif ($action == 'toggle') {
        $id = $_POST['id'] ?? 0;
        if ($id > 0) {
            $todoManager->toggleTodo($id);
        }
    }
}

header('Location: http://localhost/my_todo_app/');
exit(); 
?>