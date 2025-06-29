<?php

class TodoManager {
    private $conn; 
    private $table = 'todos';

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    public function getTodos() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTodo($task) {
        $query = "INSERT INTO " . $this->table . " (task) VALUES (:task)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':task', $task);
        return $stmt->execute();
    }

    public function toggleTodo($id) {
        // Dapatkan status 'completed' saat ini
        $query_select = "SELECT completed FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt_select = $this->conn->prepare($query_select);
        $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $current_status = $stmt_select->fetchColumn();

        // Ubah status
        $new_status = !$current_status;

        $query_update = "UPDATE " . $this->table . " SET completed = :completed WHERE id = :id";
        $stmt_update = $this->conn->prepare($query_update);
        $stmt_update->bindParam(':completed', $new_status, PDO::PARAM_BOOL);
        $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt_update->execute();
    }
}
?>
