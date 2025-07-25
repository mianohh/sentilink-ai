<?php
require_once 'config.php';

/**
 * Get or create user based on IP hash
 */
function getUserByIP($pdo) {
    $ip_hash = hash('sha256', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE ip_hash = ?");
    $stmt->execute([$ip_hash]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Create new user
        $stmt = $pdo->prepare("INSERT INTO users (ip_hash) VALUES (?)");
        $stmt->execute([$ip_hash]);
        return $pdo->lastInsertId();
    }
    
    return $user['id'];
}

/**
 * Analyze mood input and categorize it
 */
function analyzeMood($input) {
    $input = strtolower(trim($input));
    
    // Emoji mapping
    $emoji_patterns = [
        'happy' => ['😊', '😄', '😃', '😁', '🙂', '😍', '🥰', '😊'],
        'sad' => ['😢', '😭', '😞', '😔', '😪', '😿', '💔'],
        'angry' => ['😠', '😡', '🤬', '😤', '💢', '🔥'],
        'anxious' => ['😰', '😨', '😟', '😧', '😳', '🙁'],
        'excited' => ['🤩', '😆', '🎉', '🥳', '⚡', '🔥'],
        'calm' => ['😌', '😇', '🧘', '☮️', '🕊️']
    ];
    
    // Check for emojis first
    foreach ($emoji_patterns as $mood => $emojis) {
        foreach ($emojis as $emoji) {
            if (strpos($input, $emoji) !== false) {
                return $mood;
            }
        }
    }
    
    // Text-based mood analysis
    $mood_keywords = [
        'happy' => ['happy', 'joy', 'great', 'awesome', 'amazing', 'good', 'wonderful', 'fantastic', 'cheerful', 'delighted'],
        'sad' => ['sad', 'depressed', 'down', 'blue', 'unhappy', 'miserable', 'heartbroken', 'crying', 'tearful'],
        'angry' => ['angry', 'mad', 'furious', 'rage', 'annoyed', 'irritated', 'pissed', 'frustrated'],
        'anxious' => ['anxious', 'worried', 'nervous', 'stressed', 'panic', 'fear', 'scared', 'overwhelmed'],
        'excited' => ['excited', 'thrilled', 'pumped', 'energetic', 'enthusiastic', 'hyped'],
        'calm' => ['calm', 'peaceful', 'relaxed', 'serene', 'tranquil', 'zen', 'chill']
    ];
    
    foreach ($mood_keywords as $mood => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($input, $keyword) !== false) {
                return $mood;
            }
        }
    }
    
    // Default to calm if no match found
    return 'calm';
}

/**
 * Get random meme for mood category
 */
function getMemeForMood($pdo, $mood_category) {
    $stmt = $pdo->prepare("SELECT * FROM memes WHERE mood_category = ? ORDER BY RAND() LIMIT 1");
    $stmt->execute([$mood_category]);
    return $stmt->fetch();
}

/**
 * Get random insight for mood category
 */
function getInsightForMood($pdo, $mood_category) {
    $stmt = $pdo->prepare("SELECT * FROM insights WHERE mood_category = ? ORDER BY RAND() LIMIT 1");
    $stmt->execute([$mood_category]);
    return $stmt->fetch();
}

/**
 * Get community mood data for chart
 */
function getCommunityMoodData($pdo, $hours = 24) {
    $stmt = $pdo->prepare("
        SELECT mood_category, COUNT(*) as count 
        FROM mood_entries 
        WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? HOUR)
        GROUP BY mood_category
    ");
    $stmt->execute([$hours]);
    return $stmt->fetchAll();
}

/**
 * Get mood trends over time
 */
function getMoodTrends($pdo, $days = 7) {
    $stmt = $pdo->prepare("
        SELECT DATE(timestamp) as date, mood_category, COUNT(*) as count
        FROM mood_entries 
        WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
        GROUP BY DATE(timestamp), mood_category
        ORDER BY date ASC
    ");
    $stmt->execute([$days]);
    return $stmt->fetchAll();
}

/**
 * Check admin authentication
 */
function isAdminLoggedIn() {
    return isset($_SESSION[ADMIN_SESSION_NAME]) && $_SESSION[ADMIN_SESSION_NAME] === true;
}

/**
 * Authenticate admin user
 */
function authenticateAdmin($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT password_hash FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION[ADMIN_SESSION_NAME] = true;
        $_SESSION['admin_username'] = $username;
        return true;
    }
    return false;
}
?>