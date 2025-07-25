
USE sentilink_ai;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_hash VARCHAR(64) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip_hash (ip_hash)
);

-- Mood entries table
CREATE TABLE mood_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    mood_input TEXT NOT NULL,
    mood_type ENUM('emoji', 'text', 'voice') DEFAULT 'text',
    mood_category VARCHAR(50) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_timestamp (timestamp),
    INDEX idx_mood_category (mood_category)
);

-- Memes table
CREATE TABLE memes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mood_category VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_mood_category (mood_category)
);

-- Insights table
CREATE TABLE insights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mood_category VARCHAR(50) NOT NULL,
    insight_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_mood_category (mood_category)
);

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO memes (mood_category, file_path, alt_text) VALUES
('happy', 'images/memes/happy1.jpg', 'Happy dancing cat'),
('happy', 'images/memes/happy2.jpg', 'Sunshine and rainbows'),
('sad', 'images/memes/sad1.jpg', 'Comfort hug meme'),
('sad', 'images/memes/sad2.jpg', 'Its okay to not be okay'),
('angry', 'images/memes/angry1.jpg', 'Take a deep breath'),
('angry', 'images/memes/angry2.jpg', 'Anger management cat'),
('anxious', 'images/memes/anxious1.jpg', 'Breathing exercise reminder'),
('anxious', 'images/memes/anxious2.jpg', 'You got this motivational'),
('excited', 'images/memes/excited1.jpg', 'Party celebration'),
('excited', 'images/memes/excited2.jpg', 'High energy vibes'),
('calm', 'images/memes/calm1.jpg', 'Peaceful nature scene'),
('calm', 'images/memes/calm2.jpg', 'Meditation reminder');

INSERT INTO insights (mood_category, insight_text) VALUES
('happy', 'Happiness is contagious! Your positive energy can brighten someone elses day. Consider sharing your joy with others.'),
('happy', 'Studies show that expressing gratitude can amplify feelings of happiness. What are three things youre grateful for today?'),
('sad', 'Its completely normal to feel sad sometimes. These emotions help us process experiences and grow stronger.'),
('sad', 'Sadness often signals that something important to us needs attention. Take time to nurture yourself today.'),
('angry', 'Anger is often a secondary emotion hiding hurt or frustration. Try to identify what triggered this feeling.'),
('angry', 'Physical exercise can be an excellent way to channel anger constructively. Consider a quick walk or workout.'),
('anxious', 'Anxiety often stems from focusing on future uncertainties. Try grounding yourself in the present moment.'),
('anxious', 'Deep breathing activates your parasympathetic nervous system, naturally reducing anxiety levels.'),
('excited', 'Excitement and anxiety share similar physiological responses. Channel this energy into something productive!'),
('excited', 'High energy states are perfect for tackling challenging tasks or starting new projects.'),
('calm', 'Calmness is a superpower in our fast-paced world. Use this peaceful state to reflect and recharge.'),
('calm', 'Mindful moments like these help build emotional resilience for future challenges.');

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');