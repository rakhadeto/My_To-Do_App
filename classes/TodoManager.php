<?php
if (session_status() === PHP_SESSION_NONE) session_start();

class TodoManager {
    private $db;

    public function __construct($db) { $this->db = $db; }

    // ── USER STATS ──
    public function getUserStats() {
        $stmt = $this->db->prepare("SELECT * FROM user_stats WHERE id = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ── HABITS ──
    public function getHabits() {
        return $this->db->query("SELECT * FROM habits ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addHabit($task) {
        $this->db->prepare("INSERT INTO habits (task) VALUES (?)")->execute([$task]);
    }
    public function deleteHabit($id) {
        $this->db->prepare("DELETE FROM habits WHERE id = ?")->execute([$id]);
    }
    public function processHabit($id, $type) {
        $stmt = $this->db->prepare("SELECT id FROM habits WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetch()) {
            if ($type === 'up') {
                $this->addXP(1, 15);
                $this->db->prepare("UPDATE user_stats SET gold = gold + 5 WHERE id = 1")->execute();
                $_SESSION['notif'] = ['msg' => '+15 XP  +5 Gold', 'type' => 'xp'];
            } elseif ($type === 'down') {
                $this->db->prepare("UPDATE user_stats SET hp = GREATEST(0, hp - 10) WHERE id = 1")->execute();
                $this->checkDeath();
                $_SESSION['notif'] = ['msg' => '-10 HP', 'type' => 'dmg'];
            }
        }
    }

    // ── DAILIES ──
    public function getDailies() {
        $today = date('Y-m-d');
        $this->db->prepare("UPDATE dailies SET is_completed = 0 WHERE DATE(last_completed_date) < ? OR last_completed_date IS NULL")
            ->execute([$today]);
        return $this->db->query("SELECT * FROM dailies ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addDaily($task) {
        $this->db->prepare("INSERT INTO dailies (task) VALUES (?)")->execute([$task]);
    }
    public function deleteDaily($id) {
        $this->db->prepare("DELETE FROM dailies WHERE id = ?")->execute([$id]);
    }
    public function toggleDaily($id) {
        $stmt = $this->db->prepare("SELECT is_completed FROM dailies WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            $new = $row['is_completed'] ? 0 : 1;
            if ($new) {
                $this->db->prepare("UPDATE dailies SET is_completed = 1, last_completed_date = NOW() WHERE id = ?")->execute([$id]);
                $this->addXP(1, 10);
                $this->db->prepare("UPDATE user_stats SET gold = gold + 3 WHERE id = 1")->execute();
                $_SESSION['notif'] = ['msg' => 'Daily Complete! +10 XP  +3 Gold', 'type' => 'xp'];
            } else {
                $this->db->prepare("UPDATE dailies SET is_completed = 0, last_completed_date = NULL WHERE id = ?")->execute([$id]);
                $_SESSION['notif'] = ['msg' => 'Daily unmarked', 'type' => 'dmg'];
            }
        }
    }

    // ── TODOS ──
    public function getTodos() {
        return $this->db->query("SELECT * FROM todos ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addTodo($task) {
        $this->db->prepare("INSERT INTO todos (task) VALUES (?)")->execute([$task]);
    }
    public function deleteTodo($id) {
        $this->db->prepare("DELETE FROM todos WHERE id = ?")->execute([$id]);
    }
    public function toggleTodo($id) {
        $stmt = $this->db->prepare("SELECT completed FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            $new = $row['completed'] ? 0 : 1;
            $this->db->prepare("UPDATE todos SET completed = ? WHERE id = ?")->execute([$new, $id]);
            if ($new) {
                $this->addXP(1, 20);
                $this->db->prepare("UPDATE user_stats SET gold = gold + 10 WHERE id = 1")->execute();
                $_SESSION['notif'] = ['msg' => 'Quest Complete! +20 XP  +10 Gold', 'type' => 'xp'];
            } else {
                $this->db->prepare("UPDATE user_stats SET current_xp = GREATEST(0, current_xp - 20), gold = GREATEST(0, gold - 10) WHERE id = 1")->execute();
                $_SESSION['notif'] = ['msg' => '-20 XP  -10 Gold', 'type' => 'dmg'];
            }
        }
    }

    // ── REWARDS / SHOP ──
    public function getRewards() {
        return $this->db->query("SELECT * FROM rewards ORDER BY cost ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addReward($name, $cost) {
        $stmt = $this->db->prepare("INSERT INTO rewards (item_name, cost) VALUES (?, ?)");
        return $stmt->execute([$name, $cost]);
    }
    public function deleteReward($id) {
        return $this->db->prepare("DELETE FROM rewards WHERE id = ?")->execute([$id]);
    }
    public function buyReward($id) {
        $stmt = $this->db->prepare("SELECT cost FROM rewards WHERE id = ?");
        $stmt->execute([$id]);
        $item  = $stmt->fetch();
        $stats = $this->getUserStats();
        if ($item && $stats['gold'] >= $item['cost']) {
            $this->db->prepare("UPDATE user_stats SET gold = gold - ? WHERE id = 1")->execute([$item['cost']]);
            $_SESSION['notif'] = ['msg' => "Item purchased! -{$item['cost']} Gold", 'type' => 'buy'];
            return true;
        }
        $_SESSION['notif'] = ['msg' => 'Not enough Gold!', 'type' => 'dmg'];
        return false;
    }

    // ── XP ENGINE ──
    public function addXP($userId, $amount) {
        $this->db->prepare("UPDATE user_stats SET current_xp = current_xp + ? WHERE id = ?")->execute([$amount, $userId]);
        $stmt = $this->db->prepare("SELECT * FROM user_stats WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['current_xp'] >= $user['max_xp']) {
            $sisa     = $user['current_xp'] - $user['max_xp'];
            $newLevel = $user['level'] + 1;
            $newMaxXP = $user['max_xp'] + 50;
            $newMaxHP = $user['max_hp'] + 20;
            $this->db->prepare("UPDATE user_stats SET level=?, current_xp=?, max_xp=?, max_hp=?, hp=? WHERE id=?")
                ->execute([$newLevel, $sisa, $newMaxXP, $newMaxHP, $newMaxHP, $userId]);
            $_SESSION['event_levelup'] = $newLevel;
        }
    }

    private function checkDeath() {
        $stats = $this->getUserStats();
        if ($stats['hp'] <= 0) {
            $newLevel = max(1, $stats['level'] - 1);
            $this->db->prepare("UPDATE user_stats SET hp = 50, level = ?, current_xp = 0, gold = 0 WHERE id = 1")
                ->execute([$newLevel]);
            $_SESSION['event_death'] = true;
        }
    }
}
?>
