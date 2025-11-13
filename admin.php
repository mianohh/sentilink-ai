<?php
require_once 'functions.php';

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (authenticateAdmin($pdo, $username, $password)) {
        header('Location: admin.php');
        exit;
    } else {
        $login_error = "Taarifa za kuingia si sahihi";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Handle meme addition
if (isset($_POST['add_meme']) && isAdminLoggedIn()) {
    $mood_category = $_POST['meme_category'];
    $file_path = $_POST['meme_path'];
    $alt_text = $_POST['meme_alt'];
    
    $stmt = $pdo->prepare("INSERT INTO memes (mood_category, file_path, alt_text) VALUES (?, ?, ?)");
    $stmt->execute([$mood_category, $file_path, $alt_text]);
    $success_message = "Meme imeongezwa kwa mafanikio!";
}

// Handle insight addition
if (isset($_POST['add_insight']) && isAdminLoggedIn()) {
    $mood_category = $_POST['insight_category'];
    $insight_text = $_POST['insight_text'];
    
    $stmt = $pdo->prepare("INSERT INTO insights (mood_category, insight_text) VALUES (?, ?)");
    $stmt->execute([$mood_category, $insight_text]);
    $success_message = "Ufahamu umeongezwa kwa mafanikio!";
}

// Tafsiri ya hisia kutoka Kiingereza hadi Kiswahili
$mood_translations = [
    'happy' => 'furaha',
    'sad' => 'huzuni',
    'angry' => 'hasira',
    'anxious' => 'wasiwasi',
    'excited' => 'msisimko',
    'calm' => 'utulivu',
    'furaha' => 'furaha',
    'huzuni' => 'huzuni',
    'hasira' => 'hasira',
    'wasiwasi' => 'wasiwasi',
    'msisimko' => 'msisimko',
    'utulivu' => 'utulivu'
];

// Tafsiri kinyume (Kiswahili to English for display mapping)
$mood_display = [
    'happy' => 'Furaha',
    'sad' => 'Huzuni',
    'angry' => 'Hasira',
    'anxious' => 'Wasiwasi',
    'excited' => 'Msisimko',
    'calm' => 'Utulivu',
    'furaha' => 'Furaha',
    'huzuni' => 'Huzuni',
    'hasira' => 'Hasira',
    'wasiwasi' => 'Wasiwasi',
    'msisimko' => 'Msisimko',
    'utulivu' => 'Utulivu'
];

$mood_type_display = [
    'emoji' => 'Emoji',
    'text' => 'Maandishi',
    'maandishi' => 'Maandishi',
    'voice' => 'Sauti',
    'sauti' => 'Sauti'
];

// Get dashboard data
if (isAdminLoggedIn()) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM mood_entries");
    $total_entries = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $total_users = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT mood_category, COUNT(*) as count FROM mood_entries GROUP BY mood_category ORDER BY count DESC");
    $mood_stats = $stmt->fetchAll();
    
    $stmt = $pdo->query("SELECT * FROM mood_entries ORDER BY timestamp DESC LIMIT 10");
    $recent_entries = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashibodi ya Msimamizi - SentiLink AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <?php if (!isAdminLoggedIn()): ?>
            <!-- Login Form -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h3 class="mb-0">Kuingia kwa Msimamizi</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($login_error)): ?>
                                <div class="alert alert-danger"><?php echo $login_error; ?></div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Jina la Mtumiaji</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nenosiri</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" name="login" class="btn btn-primary w-100">Ingia</button>
                            </form>
                            
                            <div class="mt-3 text-center">
                                <small class="text-muted">Chaguo-msingi: admin / admin123</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Admin Dashboard -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Dashibodi ya Msimamizi</h1>
                <div>
                    <span class="me-3">Karibu, <?php echo $_SESSION['admin_username']; ?></span>
                    <a href="?logout=1" class="btn btn-outline-danger">Ondoka</a>
                </div>
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Jumla ya Maingizo</h5>
                            <h2><?php echo $total_entries; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Jumla ya Watumiaji</h5>
                            <h2><?php echo $total_users; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Mgawanyo wa Hisia</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($mood_stats as $stat): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span><?php echo $mood_display[$stat['mood_category']] ?? ucfirst($stat['mood_category']); ?></span>
                                    <span class="badge bg-secondary"><?php echo $stat['count']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Add Meme Form -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Ongeza Meme Mpya</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="meme_category" class="form-label">Kategoria ya Hisia</label>
                                    <select class="form-control" id="meme_category" name="meme_category" required>
                                        <option value="furaha">Furaha</option>
                                        <option value="huzuni">Huzuni</option>
                                        <option value="hasira">Hasira</option>
                                        <option value="wasiwasi">Wasiwasi</option>
                                        <option value="msisimko">Msisimko</option>
                                        <option value="utulivu">Utulivu</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="meme_path" class="form-label">Njia ya Picha</label>
                                    <input type="text" class="form-control" id="meme_path" name="meme_path" 
                                           placeholder="images/memes/mfano.jpg" required>
                                </div>
                                <div class="mb-3">
                                    <label for="meme_alt" class="form-label">Maelezo ya Picha</label>
                                    <input type="text" class="form-control" id="meme_alt" name="meme_alt" 
                                           placeholder="Maelezo ya meme" required>
                                </div>
                                <button type="submit" name="add_meme" class="btn btn-primary">Ongeza Meme</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Add Insight Form -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Ongeza Ufahamu Mpya</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="insight_category" class="form-label">Kategoria ya Hisia</label>
                                    <select class="form-control" id="insight_category" name="insight_category" required>
                                        <option value="furaha">Furaha</option>
                                        <option value="huzuni">Huzuni</option>
                                        <option value="hasira">Hasira</option>
                                        <option value="wasiwasi">Wasiwasi</option>
                                        <option value="msisimko">Msisimko</option>
                                        <option value="utulivu">Utulivu</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="insight_text" class="form-label">Maandishi ya Ufahamu</label>
                                    <textarea class="form-control" id="insight_text" name="insight_text" 
                                              rows="4" placeholder="Ingiza ufahamu wa kisaikolojia..." required></textarea>
                                </div>
                                <button type="submit" name="add_insight" class="btn btn-success">Ongeza Ufahamu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Entries -->
            <div class="card">
                <div class="card-header">
                    <h5>Maingizo ya Hivi Karibuni ya Hisia</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nambari</th>
                                    <th>Maingizo ya Hisia</th>
                                    <th>Aina</th>
                                    <th>Kategoria</th>
                                    <th>Muda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_entries as $entry): ?>
                                    <tr>
                                        <td><?php echo $entry['id']; ?></td>
                                        <td><?php echo htmlspecialchars(substr($entry['mood_input'], 0, 50)); ?>
                                            <?php echo strlen($entry['mood_input']) > 50 ? '...' : ''; ?></td>
                                        <td><?php echo $mood_type_display[$entry['mood_type']] ?? ucfirst($entry['mood_type']); ?></td>
                                        <td><?php echo $mood_display[$entry['mood_category']] ?? ucfirst($entry['mood_category']); ?></td>
                                        <td><?php echo date('M j, Y H:i', strtotime($entry['timestamp'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>