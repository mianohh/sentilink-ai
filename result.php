<?php
require_once 'functions.php';

// Process form submission
if ($_POST) {
    $user_id = getUserByIP($pdo);
    $mood_input = '';
    $mood_type = $_POST['mood_type'] ?? 'text';
    
    // Determine mood input based on type
    if (!empty($_POST['mood_emoji'])) {
        $mood_input = $_POST['mood_emoji'];
        $mood_type = 'emoji';
    } elseif (!empty($_POST['mood_text'])) {
        $mood_input = $_POST['mood_text'];
        $mood_type = 'text';
    } else {
        // Redirect back if no input
        header('Location: index.php');
        exit;
    }
    
    // Analyze mood
    $mood_category = analyzeMood($mood_input);
    
    // Store mood entry
    $stmt = $pdo->prepare("INSERT INTO mood_entries (user_id, mood_input, mood_type, mood_category) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $mood_input, $mood_type, $mood_category]);
    
    // Get meme and insight
    $meme = getMemeForMood($pdo, $mood_category);
    $insight = getInsightForMood($pdo, $mood_category);
    $community_data = getCommunityMoodData($pdo);
    
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Mood Analysis - SentiLink AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <!-- Mood Analysis Results -->
                <div class="card shadow-lg mb-4">
                    <div class="card-header bg-success text-white text-center">
                        <h2 class="mb-0">Your Mood Analysis</h2>
                        <p class="mb-0">Detected mood: <strong><?php echo ucfirst($mood_category); ?></strong></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Your Mood Meme 🎭</h4>
                                <?php if ($meme): ?>
                                    <div class="meme-container">
                                        <div class="placeholder-meme bg-light border rounded p-4 text-center">
                                            <h5><?php echo htmlspecialchars($meme['alt_text']); ?></h5>
                                            <p class="text-muted">Meme: <?php echo htmlspecialchars($meme['file_path']); ?></p>
                                            <div class="emoji-large">
                                                <?php 
                                                $mood_emojis = [
                                                    'happy' => '😊',
                                                    'sad' => '😢',
                                                    'angry' => '😠',
                                                    'anxious' => '😰',
                                                    'excited' => '🤩',
                                                    'calm' => '😌'
                                                ];
                                                echo $mood_emojis[$mood_category] ?? '🙂';
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h4>Psychological Insight 🧠</h4>
                                <?php if ($insight): ?>
                                    <div class="insight-box bg-light p-3 rounded">
                                        <p class="mb-0"><?php echo htmlspecialchars($insight['insight_text']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="index.php" class="btn btn-primary">Share Another Mood</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Community Mood Flow -->
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Community Mood Flow (24h)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="moodChart" width="300" height="300"></canvas>
                    </div>
                </div>
                
                <!-- Mood Stats -->
                <div class="card shadow mt-3">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Recent Mood Statistics</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($community_data as $mood_data): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?php echo ucfirst($mood_data['mood_category']); ?></span>
                                <span class="badge bg-primary"><?php echo $mood_data['count']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Community Mood Chart
        const ctx = document.getElementById('moodChart').getContext('2d');
        const moodData = <?php echo json_encode($community_data); ?>;
        
        const labels = moodData.map(item => item.mood_category);
        const data = moodData.map(item => item.count);
        const colors = {
            'happy': '#FFD700',
            'sad': '#4169E1',
            'angry': '#DC143C',
            'anxious': '#FF8C00',
            'excited': '#FF1493',
            'calm': '#32CD32'
        };
        
        const backgroundColors = labels.map(label => colors[label] || '#808080');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>