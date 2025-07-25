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
        $login_error = "Invalid credentials";
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
    $success_message = "Meme added successfully!";
}

// Handle insight addition
if (isset($_POST['add_insight']) && isAdminLoggedIn()) {
    $mood_category = $_POST['insight_category'];
    $insight_text = $_POST['insight_text'];
    
    $stmt = $pdo->prepare("INSERT INTO insights (mood_category, insight_text) VALUES (?, ?)");
    $stmt->execute([$mood_category, $insight_text]);
    $success_message = "Insight added successfully!";
}

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SentiLink AI</title>
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
                            <h3 class="mb-0">Admin Login</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($login_error)): ?>
                                <div class="alert alert-danger"><?php echo $login_error; ?></div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                            </form>
                            
                            <div class="mt-3 text-center">
                                <small class="text-muted">Default: admin / admin123</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Admin Dashboard -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Admin Dashboard</h1>
                <div>
                    <span class="me-3">Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                    <a href="?logout=1" class="btn btn-outline-danger">Logout</a>
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
                            <h5>Total Entries</h5>
                            <h2><?php echo $total_entries; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Total Users</h5>
                            <h2><?php echo $total_users; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Mood Distribution</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($mood_stats as $stat): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span><?php echo ucfirst($stat['mood_category']); ?></span>
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
                            <h5>Add New Meme</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="meme_category" class="form-label">Mood Category</label>
                                    <select class="form-control" id="meme_category" name="meme_category" required>
                                        <option value="happy">Happy</option>
                                        <option value="sad">Sad</option>
                                        <option value="angry">Angry</option>
                                        <option value="anxious">Anxious</option>
                                        <option value="excited">Excited</option>
                                        <option value="calm">Calm</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="meme_path" class="form-label">Image Path</label>
                                    <input type="text" class="form-control" id="meme_path" name="meme_path" 
                                           placeholder="images/memes/example.jpg" required>
                                </div>
                                <div class="mb-3">
                                    <label for="meme_alt" class="form-label">Alt Text</label>
                                    <input type="text" class="form-control" id="meme_alt" name="meme_alt" 
                                           placeholder="Description of the meme" required>
                                </div>
                                <button type="submit" name="add_meme" class="btn btn-primary">Add Meme</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Add Insight Form -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Add New Insight</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="insight_category" class="form-label">Mood Category</label>
                                    <select class="form-control" id="insight_category" name="insight_category" required>
                                        <option value="happy">Happy</option>
                                        <option value="sad">Sad</option>
                                        <option value="angry">Angry</option>
                                        <option value="anxious">Anxious</option>
                                        <option value="excited">Excited</option>
                                        <option value="calm">Calm</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="insight_text" class="form-label">Insight Text</label>
                                    <textarea class="form-control" id="insight_text" name="insight_text" 
                                              rows="4" placeholder="Enter psychological insight..." required></textarea>
                                </div>
                                <button type="submit" name="add_insight" class="btn btn-success">Add Insight</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Entries -->
            <div class="card">
                <div class="card-header">
                    <h5>Recent Mood Entries</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mood Input</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_entries as $entry): ?>
                                    <tr>
                                        <td><?php echo $entry['id']; ?></td>
                                        <td><?php echo htmlspecialchars(substr($entry['mood_input'], 0, 50)); ?>
                                            <?php echo strlen($entry['mood_input']) > 50 ? '...' : ''; ?></td>
                                        <td><?php echo ucfirst($entry['mood_type']); ?></td>
                                        <td><?php echo ucfirst($entry['mood_category']); ?></td>
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