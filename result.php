<?php
require_once 'functions.php';

// Process form submission
if ($_POST) {
    $user_id = getUserByIP($pdo);
    $mood_input = '';
    $mood_type = $_POST['mood_type'] ?? 'maandishi';
    
    // Determine mood input based on type
    if (!empty($_POST['mood_emoji'])) {
        $mood_input = $_POST['mood_emoji'];
        $mood_type = 'emoji';
    } elseif (!empty($_POST['mood_text'])) {
        $mood_input = $_POST['mood_text'];
        $mood_type = 'maandishi';
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

// Translation array for mood categories
$mood_translations = [
    'furaha' => 'Furaha',
    'huzuni' => 'Huzuni',
    'hasira' => 'Hasira',
    'wasiwasi' => 'Wasiwasi',
    'msisimko' => 'Msisimko',
    'utulivu' => 'Utulivu'
];

$mood_display = $mood_translations[$mood_category] ?? ucfirst($mood_category);
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uchambuzi wa Hisia Zako - SentiLink AI</title>
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
                        <h2 class="mb-0">Uchambuzi wa Hisia Zako</h2>
                        <p class="mb-0">Hisia iliyogunduliwa: <strong><?php echo $mood_display; ?></strong></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Picha ya Hisia Yako 🎭</h4>
                                <?php if ($meme): ?>
                                    <div class="meme-container">
                                        <div class="placeholder-meme bg-light border rounded p-4 text-center">
                                            <h5><?php echo htmlspecialchars($meme['alt_text']); ?></h5>
                                            <p class="text-muted">Picha: <?php echo htmlspecialchars($meme['file_path']); ?></p>
                                            <div class="emoji-large">
                                                <?php 
                                                $mood_emojis = [
                                                    'furaha' => '😊',
                                                    'huzuni' => '😢',
                                                    'hasira' => '😠',
                                                    'wasiwasi' => '😰',
                                                    'msisimko' => '🤩',
                                                    'utulivu' => '😌'
                                                ];
                                                echo $mood_emojis[$mood_category] ?? '🙂';
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h4>Maarifa ya Kisaikolojia 🧠</h4>
                                <?php if ($insight): ?>
                                    <div class="insight-box bg-light p-3 rounded">
                                        <p class="mb-0"><?php echo htmlspecialchars($insight['insight_text']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="index.php" class="btn btn-primary">Shiriki Hisia Nyingine</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Community Mood Flow -->
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Mtiririko wa Hisia za Jamii (Masaa 24)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="moodChart" width="300" height="300"></canvas>
                    </div>
                </div>
                
                <!-- Mood Stats -->
                <div class="card shadow mt-3">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Takwimu za Hisia za Hivi Karibuni</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($community_data as $mood_data): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?php echo $mood_translations[$mood_data['mood_category']] ?? ucfirst($mood_data['mood_category']); ?></span>
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
        
        // Translation object for chart labels
        const moodTranslations = {
            'furaha': 'Furaha',
            'huzuni': 'Huzuni',
            'hasira': 'Hasira',
            'wasiwasi': 'Wasiwasi',
            'msisimko': 'Msisimko',
            'utulivu': 'Utulivu'
        };
        
        const labels = moodData.map(item => moodTranslations[item.mood_category] || item.mood_category);
        const data = moodData.map(item => item.count);
        const colors = {
            'furaha': '#FFD700',
            'huzuni': '#4169E1',
            'hasira': '#DC143C',
            'wasiwasi': '#FF8C00',
            'msisimko': '#FF1493',
            'utulivu': '#32CD32'
        };
        
        const backgroundColors = moodData.map(item => colors[item.mood_category] || '#808080');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
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