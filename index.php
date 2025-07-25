<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SentiLink AI - Share Your Mood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h1 class="mb-0">🧠 SentiLink AI</h1>
                        <p class="mb-0">Connect with your emotions and community</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="result.php" method="POST" id="moodForm">
                            <!-- Emoji Picker Section -->
                            <div class="mb-4">
                                <label class="form-label h5">How are you feeling? Pick an emoji:</label>
                                <div class="emoji-grid">
                                    <input type="radio" name="mood_emoji" value="😊" id="happy1" class="emoji-input">
                                    <label for="happy1" class="emoji-label">😊</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😄" id="happy2" class="emoji-input">
                                    <label for="happy2" class="emoji-label">😄</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😢" id="sad1" class="emoji-input">
                                    <label for="sad1" class="emoji-label">😢</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😭" id="sad2" class="emoji-input">
                                    <label for="sad2" class="emoji-label">😭</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😠" id="angry1" class="emoji-input">
                                    <label for="angry1" class="emoji-label">😠</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😡" id="angry2" class="emoji-input">
                                    <label for="angry2" class="emoji-label">😡</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😰" id="anxious1" class="emoji-input">
                                    <label for="anxious1" class="emoji-label">😰</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😨" id="anxious2" class="emoji-input">
                                    <label for="anxious2" class="emoji-label">😨</label>
                                    
                                    <input type="radio" name="mood_emoji" value="🤩" id="excited1" class="emoji-input">
                                    <label for="excited1" class="emoji-label">🤩</label>
                                    
                                    <input type="radio" name="mood_emoji" value="🥳" id="excited2" class="emoji-input">
                                    <label for="excited2" class="emoji-label">🥳</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😌" id="calm1" class="emoji-input">
                                    <label for="calm1" class="emoji-label">😌</label>
                                    
                                    <input type="radio" name="mood_emoji" value="🧘" id="calm2" class="emoji-input">
                                    <label for="calm2" class="emoji-label">🧘</label>
                                </div>
                            </div>
                            
                            <div class="text-center mb-3">
                                <strong>OR</strong>
                            </div>
                            
                            <!-- Text Input Section -->
                            <div class="mb-4">
                                <label for="mood_text" class="form-label h5">Describe your feelings in words:</label>
                                <textarea class="form-control" id="mood_text" name="mood_text" rows="3" 
                                    placeholder="Tell us how you're feeling today..."></textarea>
                            </div>
                            
                            <!-- Voice Input Section -->
                            <div class="mb-4">
                                <label class="form-label h5">Or speak your feelings:</label>
                                <div class="d-flex gap-2">
                                    <button type="button" id="startVoice" class="btn btn-outline-primary">
                                        🎤 Start Recording
                                    </button>
                                    <button type="button" id="stopVoice" class="btn btn-outline-danger" style="display:none;">
                                        ⏹️ Stop Recording
                                    </button>
                                </div>
                                <div id="voiceStatus" class="mt-2 text-muted"></div>
                            </div>
                            
                            <input type="hidden" name="mood_type" id="mood_type" value="text">
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    Analyze My Mood 🔍
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Your data helps build community insights while staying anonymous
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>