<?php
session_start();
require_once 'database.php';
require_once 'TodoManager.php';

$db      = (new Database())->connect();
$manager = new TodoManager($db);

// ── HANDLE ACTIONS ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Habits
    if ($action === 'add_habit' && !empty($_POST['task']))       $manager->addHabit(trim($_POST['task']));
    if ($action === 'delete_habit' && !empty($_POST['id']))      $manager->deleteHabit((int)$_POST['id']);
    if ($action === 'process_habit' && !empty($_POST['id']))     $manager->processHabit((int)$_POST['id'], $_POST['type'] ?? 'up');

    // Dailies
    if ($action === 'add_daily' && !empty($_POST['task']))       $manager->addDaily(trim($_POST['task']));
    if ($action === 'delete_daily' && !empty($_POST['id']))      $manager->deleteDaily((int)$_POST['id']);
    if ($action === 'toggle_daily' && !empty($_POST['id']))      $manager->toggleDaily((int)$_POST['id']);

    // Todos
    if ($action === 'add_todo' && !empty($_POST['task']))        $manager->addTodo(trim($_POST['task']));
    if ($action === 'delete_todo' && !empty($_POST['id']))       $manager->deleteTodo((int)$_POST['id']);
    if ($action === 'toggle_todo' && !empty($_POST['id']))       $manager->toggleTodo((int)$_POST['id']);

    // Rewards
    if ($action === 'add_reward' && !empty($_POST['name']))      $manager->addReward(trim($_POST['name']), (int)($_POST['cost'] ?? 10));
    if ($action === 'delete_reward' && !empty($_POST['id']))     $manager->deleteReward((int)$_POST['id']);
    if ($action === 'buy_reward' && !empty($_POST['id']))        $manager->buyReward((int)$_POST['id']);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// ── FETCH DATA ──
$stats   = $manager->getUserStats();
$habits  = $manager->getHabits();
$dailies = $manager->getDailies();
$todos   = $manager->getTodos();
$rewards = $manager->getRewards();

// ── POP EVENTS ──
$eventLevelUp = $_SESSION['event_levelup'] ?? null;
$eventDeath   = $_SESSION['event_death']   ?? null;
$notif        = $_SESSION['notif']         ?? null;
unset($_SESSION['event_levelup'], $_SESSION['event_death'], $_SESSION['notif']);

// ── CALC STATS ──
$hpPct = $stats['max_hp'] > 0 ? round(($stats['hp'] / $stats['max_hp']) * 100) : 0;
$xpPct = $stats['max_xp'] > 0 ? round(($stats['current_xp'] / $stats['max_xp']) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SAO Quest Manager — Naufal</title>
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="style.css"/>
</head>
<body>

<!-- CURSOR -->
<div id="sao-cursor"></div>
<div id="sao-cursor-ring"></div>

<!-- BG -->
<video id="bg-video" src="bg.mp4" autoplay muted loop playsinline></video>
<div class="bg-overlay"></div>
<div class="hex-grid"></div>

<!-- AUDIO -->
<audio id="bgMusic" src="musik.mp3" loop></audio>

<!-- NOTIFICATIONS -->
<div class="notif-container" id="notif-container"></div>

<!-- ══════════════════════════════════════════ -->
<!-- LEVEL UP MODAL -->
<?php if ($eventLevelUp): ?>
<div class="modal-overlay" id="levelup-modal">
  <div class="modal-box levelup">
    <div class="modal-particles"></div>
    <div class="modal-title">// SYSTEM NOTIFICATION //</div>
    <div class="modal-big">LEVEL UP!</div>
    <div class="modal-level"><?= $eventLevelUp ?></div>
    <div class="modal-sub">Your stats have increased.<br>You are getting stronger, Adventurer.</div>
    <button class="modal-close" onclick="document.getElementById('levelup-modal').remove()">[ CONTINUE ]</button>
  </div>
</div>
<?php endif; ?>

<!-- DEATH MODAL -->
<?php if ($eventDeath): ?>
<div class="modal-overlay" id="death-modal">
  <div class="modal-box death">
    <div class="modal-particles"></div>
    <div class="modal-title">// GAME OVER //</div>
    <div class="modal-big">YOU DIED</div>
    <div class="modal-sub" style="color:#ff6666;">HP reached zero.<br>Level down. All gold lost.<br><br><em>"The only difference between a winner<br>and a loser is: a winner gets up."</em></div>
    <button class="modal-close" onclick="document.getElementById('death-modal').remove()">[ RESPAWN ]</button>
  </div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════ -->
<div class="sao-app">

  <!-- HEADER -->
  <header class="sao-header">
    <div class="sao-logo">
      <div class="sao-logo-icon">⚔</div>
      <div>
        <div class="sao-logo-text">QUEST MANAGER</div>
        <div class="sao-logo-sub">Aincrad Task System v2.0</div>
      </div>
    </div>
    <div class="header-right">
      <span class="system-time" id="system-time">00:00:00</span>
      <button class="music-btn" id="musicBtn">♪ PLAY BGM</button>
    </div>
  </header>

  <!-- PLAYER HUD -->
  <div class="player-hud">
    <!-- Avatar -->
    <div class="avatar-wrap">
      <div class="avatar-hex"></div>
      <img class="avatar-img" src="avatar.png" alt="Player Avatar"/>
      <div class="level-badge">LV.<?= $stats['level'] ?></div>
    </div>

    <!-- Stats -->
    <div class="stats-section">
      <!-- HP -->
      <div class="stat-row">
        <div class="stat-header">
          <span class="stat-label" style="color:var(--sao-red)">❤ Hit Points</span>
          <span class="stat-val"><?= $stats['hp'] ?> / <?= $stats['max_hp'] ?></span>
        </div>
        <div class="progress-track">
          <div class="progress-fill hp-fill" data-width="<?= $hpPct ?>%" style="width:<?= $hpPct ?>%"></div>
        </div>
      </div>
      <!-- XP -->
      <div class="stat-row">
        <div class="stat-header">
          <span class="stat-label" style="color:var(--sao-gold)">★ Experience</span>
          <span class="stat-val"><?= $stats['current_xp'] ?> / <?= $stats['max_xp'] ?> XP</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill xp-fill" data-width="<?= $xpPct ?>%" style="width:<?= $xpPct ?>%"></div>
        </div>
      </div>
    </div>

    <!-- Gold -->
    <div class="gold-panel">
      <div class="gold-icon">◈</div>
      <div class="gold-amount"><?= $stats['gold'] ?></div>
      <div class="gold-label">Gold Col</div>
    </div>
  </div>

  <!-- ── QUEST BOARD ── -->
  <div class="quest-board">

    <!-- HABITS -->
    <div class="quest-panel">
      <div class="panel-header habit-header">
        <span class="panel-icon">⚡</span>
        <span class="panel-title habit-title">Habits</span>
        <span class="panel-count"><?= count($habits) ?> QUESTS</span>
      </div>
      <div class="panel-body">
        <?php if (empty($habits)): ?>
          <div class="empty-state">// NO HABITS REGISTERED //</div>
        <?php else: ?>
          <?php foreach ($habits as $h): ?>
          <div class="task-item habit-item">
            <span class="task-text"><?= htmlspecialchars($h['task']) ?></span>
            <div class="habit-controls">
              <form method="POST" style="display:inline">
                <input type="hidden" name="action" value="process_habit"/>
                <input type="hidden" name="id" value="<?= $h['id'] ?>"/>
                <input type="hidden" name="type" value="up"/>
                <button type="submit" class="hc-btn hc-plus" title="+XP +Gold">+</button>
              </form>
              <form method="POST" style="display:inline">
                <input type="hidden" name="action" value="process_habit"/>
                <input type="hidden" name="id" value="<?= $h['id'] ?>"/>
                <input type="hidden" name="type" value="down"/>
                <button type="submit" class="hc-btn hc-minus" title="-HP">−</button>
              </form>
              <form method="POST" style="display:inline">
                <input type="hidden" name="action" value="delete_habit"/>
                <input type="hidden" name="id" value="<?= $h['id'] ?>"/>
                <button type="submit" class="del-btn" title="Delete">✕</button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <form class="add-form" method="POST">
        <input type="hidden" name="action" value="add_habit"/>
        <input class="add-input" type="text" name="task" placeholder="New habit..."/>
        <button type="submit" class="add-btn gold-btn">+ ADD</button>
      </form>
    </div>

    <!-- DAILIES -->
    <div class="quest-panel">
      <div class="panel-header daily-header">
        <span class="panel-icon">🗓</span>
        <span class="panel-title daily-title">Dailies</span>
        <span class="panel-count"><?= count($dailies) ?> QUESTS</span>
      </div>
      <div class="panel-body">
        <?php if (empty($dailies)): ?>
          <div class="empty-state">// NO DAILIES REGISTERED //</div>
        <?php else: ?>
          <?php foreach ($dailies as $d): ?>
          <div class="task-item daily-item <?= $d['is_completed'] ? 'completed' : '' ?>">
            <form method="POST" style="display:contents">
              <input type="hidden" name="action" value="toggle_daily"/>
              <input type="hidden" name="id" value="<?= $d['id'] ?>"/>
              <button type="submit" class="task-check <?= $d['is_completed'] ? 'checked' : '' ?>"
                title="Toggle"><?= $d['is_completed'] ? '✓' : '' ?></button>
            </form>
            <span class="task-text"><?= htmlspecialchars($d['task']) ?></span>
            <?php if ($d['is_completed']): ?>
              <span class="daily-done-badge">DONE</span>
            <?php endif; ?>
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="delete_daily"/>
              <input type="hidden" name="id" value="<?= $d['id'] ?>"/>
              <button type="submit" class="del-btn">✕</button>
            </form>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <form class="add-form" method="POST">
        <input type="hidden" name="action" value="add_daily"/>
        <input class="add-input" type="text" name="task" placeholder="New daily..."/>
        <button type="submit" class="add-btn">+ ADD</button>
      </form>
    </div>

    <!-- TODOS -->
    <div class="quest-panel">
      <div class="panel-header todo-header">
        <span class="panel-icon">📋</span>
        <span class="panel-title todo-title">To-Do</span>
        <span class="panel-count"><?= count($todos) ?> QUESTS</span>
      </div>
      <div class="panel-body">
        <?php if (empty($todos)): ?>
          <div class="empty-state">// NO QUESTS REGISTERED //</div>
        <?php else: ?>
          <?php foreach ($todos as $t): ?>
          <div class="task-item todo-item <?= $t['completed'] ? 'completed' : '' ?>">
            <form method="POST" style="display:contents">
              <input type="hidden" name="action" value="toggle_todo"/>
              <input type="hidden" name="id" value="<?= $t['id'] ?>"/>
              <button type="submit" class="task-check <?= $t['completed'] ? 'checked' : '' ?>"
                title="Toggle"><?= $t['completed'] ? '✓' : '' ?></button>
            </form>
            <span class="task-text"><?= htmlspecialchars($t['task']) ?></span>
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="delete_todo"/>
              <input type="hidden" name="id" value="<?= $t['id'] ?>"/>
              <button type="submit" class="del-btn">✕</button>
            </form>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <form class="add-form" method="POST">
        <input type="hidden" name="action" value="add_todo"/>
        <input class="add-input" type="text" name="task" placeholder="New quest..."/>
        <button type="submit" class="add-btn purple-btn">+ ADD</button>
      </form>
    </div>

  </div><!-- end quest-board -->

  <!-- ── REWARD SHOP ── -->
  <div class="shop-panel">
    <div class="shop-header">
      <span style="font-size:1.2rem">◈</span>
      <span class="shop-title">Item Shop</span>
      <span style="margin-left:auto;font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim)">
        Balance: <span style="color:var(--sao-gold)"><?= $stats['gold'] ?> Gold</span>
      </span>
    </div>
    <div class="shop-body">
      <?php if (empty($rewards)): ?>
        <div class="empty-state" style="grid-column:1/-1">// SHOP IS EMPTY //</div>
      <?php else: ?>
        <?php foreach ($rewards as $r): ?>
        <div class="reward-item">
          <span class="reward-name"><?= htmlspecialchars($r['item_name']) ?></span>
          <span class="reward-cost">◈ <?= $r['cost'] ?></span>
          <form method="POST" style="display:inline">
            <input type="hidden" name="action" value="buy_reward"/>
            <input type="hidden" name="id" value="<?= $r['id'] ?>"/>
            <button type="submit" class="buy-btn">BUY</button>
          </form>
          <form method="POST" style="display:inline">
            <input type="hidden" name="action" value="delete_reward"/>
            <input type="hidden" name="id" value="<?= $r['id'] ?>"/>
            <button type="submit" class="del-btn">✕</button>
          </form>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <form class="shop-add-form" method="POST">
      <input type="hidden" name="action" value="add_reward"/>
      <input type="text" name="name" placeholder="New reward item..." required/>
      <input type="number" name="cost" class="shop-cost-input" placeholder="Cost" min="1" value="10" required/>
      <button type="submit" class="add-btn gold-btn">+ ADD ITEM</button>
    </form>
  </div>

  <footer style="text-align:center;padding:20px;font-family:'Share Tech Mono',monospace;font-size:0.6rem;letter-spacing:3px;color:var(--text-dim);border-top:1px solid var(--border);">
    ⚔ SAO QUEST MANAGER · NAUFAL RAKHADETO · AINCRAD SYSTEM ⚔
  </footer>

</div><!-- end sao-app -->

<script src="script.js"></script>
<?php if ($notif): ?>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    showNotif(<?= json_encode($notif['msg']) ?>, <?= json_encode($notif['type']) ?>);
  });
</script>
<?php endif; ?>
</body>
</html>
