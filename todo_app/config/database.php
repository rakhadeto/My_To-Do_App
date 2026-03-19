<?php
class Database {
    private $host     = 'localhost';
    private $db_name  = 'todo_app';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("
            <div style='font-family:monospace;background:#0a0010;color:#ff3355;padding:30px;border:1px solid #ff3355;margin:40px auto;max-width:600px;border-radius:4px;'>
                <h2 style='color:#ff3355;font-size:1rem;letter-spacing:3px;'>⚠ DATABASE CONNECTION FAILED</h2>
                <p style='color:#aaa;margin-top:12px;'>" . $e->getMessage() . "</p>
                <hr style='border-color:#ff335533;margin:16px 0;'>
                <p style='color:#666;font-size:0.85rem;'>Pastikan XAMPP MySQL sudah Running dan database <b style='color:#ff9900'>todo_app</b> sudah dibuat.</p>
            </div>");
        }
        return $this->conn;
    }
}
?>
