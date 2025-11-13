-- ============================================
-- SENTILINK AI - USANIDI WA DATABASE (KISWAHILI)
-- ============================================

USE if0_39555079_sentilink_ai;

-
-- ============================================
-- JEDWALI LA MAINGIZO YA HISIA (MOOD ENTRIES TABLE)
-- ============================================
CREATE TABLE IF NOT EXISTS mood_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    mood_input TEXT NOT NULL,
    mood_type ENUM('emoji', 'maandishi', 'sauti') DEFAULT 'maandishi',
    mood_category VARCHAR(50) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_timestamp (timestamp),
    INDEX idx_mood_category (mood_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- JEDWALI LA MEME (MEMES TABLE)
-- ============================================
CREATE TABLE IF NOT EXISTS memes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mood_category VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_mood_category (mood_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- JEDWALI LA MAARIFA (INSIGHTS TABLE)
-- ============================================
CREATE TABLE IF NOT EXISTS insights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mood_category VARCHAR(50) NOT NULL,
    insight_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_mood_category (mood_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- ============================================
-- INGIZA DATA YA MEME (INSERT MEME DATA)
-- ============================================

-- Meme za Furaha (Happy Memes)
INSERT INTO memes (mood_category, file_path, alt_text) VALUES
('furaha', 'images/memes/furaha1.jpg', 'Paka anayecheza kwa furaha'),
('furaha', 'images/memes/furaha2.jpg', 'Jua na upinde wa mvua');

-- Meme za Huzuni (Sad Memes)
INSERT INTO memes (mood_category, file_path, alt_text) VALUES
('huzuni', 'images/memes/huzuni1.jpg', 'Meme ya kukumbatia kwa faraja'),
('huzuni', 'images/memes/huzuni2.jpg', 'Ni sawa kutokuwa sawa');

-- Meme za Hasira (Angry Memes)
INSERT INTO memes (mood_category, file_path, alt_text) VALUES
('hasira', 'images/memes/hasira1.jpg', 'Pumua kwa kina'),
('hasira', 'images/memes/hasira2.jpg', 'Paka wa kudhibiti hasira');

-- Meme za Wasiwasi (Anxious Memes)
INSERT INTO memes (mood_category, file_path, alt_text) VALUES
('wasiwasi', 'images/memes/wasiwasi1.jpg', 'Ukumbusho wa zoezi la kupumua'),
('wasiwasi', 'images/memes/wasiwasi2.jpg', 'Unaweza - ukumbusho wa motisha');

-- Meme za Msisimko (Excited Memes)
INSERT INTO memes (mood_category, file_path, alt_text) VALUES
('msisimko', 'images/memes/msisimko1.jpg', 'Sherehe ya karamu'),
('msisimko', 'images/memes/msisimko2.jpg', 'Nishati ya juu');

-- Meme za Utulivu (Calm Memes)
INSERT INTO memes (mood_category, file_path, alt_text) VALUES
('utulivu', 'images/memes/utulivu1.jpg', 'Mandhari ya asili yenye amani'),
('utulivu', 'images/memes/utulivu2.jpg', 'Ukumbusho wa kutafakari');

-- ============================================
-- INGIZA MAARIFA YA KISAIKOLOJIA (INSERT PSYCHOLOGICAL INSIGHTS)
-- ============================================

-- Maarifa ya Furaha (Happiness Insights)
INSERT INTO insights (mood_category, insight_text) VALUES
('furaha', 'Furaha ni ya kuambukiza! Nishati yako chanya inaweza kuangaza siku ya mtu mwingine. Fikiria kushiriki furaha yako na wengine.'),
('furaha', 'Tafiti zinaonyesha kwamba kuelezea shukrani kunaweza kuongeza hisia za furaha. Ni mambo matatu gani unayoshukuru leo?');

-- Maarifa ya Huzuni (Sadness Insights)
INSERT INTO insights (mood_category, insight_text) VALUES
('huzuni', 'Ni kawaida kabisa kuhisi huzuni wakati mwingine. Hisia hizi zinasaidia kuchakata uzoefu na kukua nguvu zaidi.'),
('huzuni', 'Huzuni mara nyingi huashiria kwamba kitu muhimu kwetu kinahitaji umakini. Chukua muda kujitunza leo.');

-- Maarifa ya Hasira (Anger Insights)
INSERT INTO insights (mood_category, insight_text) VALUES
('hasira', 'Hasira mara nyingi ni hisia ya pili inayoficha maumivu au kufadhaika. Jaribu kutambua ni nini kilichosababisha hisia hii.'),
('hasira', 'Mazoezi ya kimwili yanaweza kuwa njia nzuri ya kutumia hasira kwa ufanisi. Fikiria kutembea au kufanya mazoezi haraka.');

-- Maarifa ya Wasiwasi (Anxiety Insights)
INSERT INTO insights (mood_category, insight_text) VALUES
('wasiwasi', 'Wasiwasi mara nyingi hutokana na kuzingatia mambo yasiyo na uhakika ya siku zijazo. Jaribu kujiweka katika wakati wa sasa.'),
('wasiwasi', 'Kupumua kwa kina huamsha mfumo wako wa parasympathetic, ukipunguza kiwango cha wasiwasi kwa asili.');

-- Maarifa ya Msisimko (Excitement Insights)
INSERT INTO insights (mood_category, insight_text) VALUES
('msisimko', 'Msisimko na wasiwasi wanashiriki majibu sawa ya kimwili. Elekeza nishati hii katika kitu chenye tija!'),
('msisimko', 'Hali za nishati ya juu ni bora kwa kushughulikia kazi ngumu au kuanza miradi mipya.');

-- Maarifa ya Utulivu (Calmness Insights)
INSERT INTO insights (mood_category, insight_text) VALUES
('utulivu', 'Utulivu ni nguvu kubwa katika ulimwengu wetu wa haraka. Tumia hali hii ya amani kufikiria na kujaza nguvu.'),
('utulivu', 'Wakati wa kutafakari kama hivi husaidia kujenga ustahimilivu wa kihisia kwa changamoto za siku zijazo.');

-- ============================================
-- INGIZA MTUMIAJI WA KIUTAWALA WA CHAGUO-MSINGI
-- (INSERT DEFAULT ADMIN USER)
-- Jina la mtumiaji: admin
-- Nywila: admin123
-- ============================================

-- ============================================
-- MWISHO WA USANIDI - DATABASE IMEANDALIWA!
-- END OF SETUP - DATABASE IS READY!
-- ============================================