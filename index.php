<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SentiLink AI - Shiriki Hisia Zako</title>
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
                        <p class="mb-0">Unganisha na hisia zako na jamii</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="result.php" method="POST" id="moodForm">
                            <!-- Emoji Picker Section -->
                            <div class="mb-4">
                                <label class="form-label h5">Unahisije leo? Chagua emoji:</label>
                                <div class="emoji-grid">
                                    <input type="radio" name="mood_emoji" value="😊" id="furaha1" class="emoji-input">
                                    <label for="furaha1" class="emoji-label" title="Furaha">😊</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😄" id="furaha2" class="emoji-input">
                                    <label for="furaha2" class="emoji-label" title="Furaha">😄</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😢" id="huzuni1" class="emoji-input">
                                    <label for="huzuni1" class="emoji-label" title="Huzuni">😢</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😭" id="huzuni2" class="emoji-input">
                                    <label for="huzuni2" class="emoji-label" title="Huzuni">😭</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😠" id="hasira1" class="emoji-input">
                                    <label for="hasira1" class="emoji-label" title="Hasira">😠</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😡" id="hasira2" class="emoji-input">
                                    <label for="hasira2" class="emoji-label" title="Hasira">😡</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😰" id="wasiwasi1" class="emoji-input">
                                    <label for="wasiwasi1" class="emoji-label" title="Wasiwasi">😰</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😨" id="wasiwasi2" class="emoji-input">
                                    <label for="wasiwasi2" class="emoji-label" title="Wasiwasi">😨</label>
                                    
                                    <input type="radio" name="mood_emoji" value="🤩" id="msisimko1" class="emoji-input">
                                    <label for="msisimko1" class="emoji-label" title="Msisimko">🤩</label>
                                    
                                    <input type="radio" name="mood_emoji" value="🥳" id="msisimko2" class="emoji-input">
                                    <label for="msisimko2" class="emoji-label" title="Msisimko">🥳</label>
                                    
                                    <input type="radio" name="mood_emoji" value="😌" id="utulivu1" class="emoji-input">
                                    <label for="utulivu1" class="emoji-label" title="Utulivu">😌</label>
                                    
                                    <input type="radio" name="mood_emoji" value="🧘" id="utulivu2" class="emoji-input">
                                    <label for="utulivu2" class="emoji-label" title="Utulivu">🧘</label>
                                </div>
                            </div>
                            
                            <div class="text-center mb-3">
                                <strong>AU</strong>
                            </div>
                            
                            <!-- Text Input Section -->
                            <div class="mb-4">
                                <label for="mood_text" class="form-label h5">Eleza hisia zako kwa maneno:</label>
                                <textarea class="form-control" id="mood_text" name="mood_text" rows="3" 
                                    placeholder="Tuambie unahisije leo..."></textarea>
                            </div>
                            
                            <!-- Voice Input Section -->
                            <div class="mb-4">
                                <label class="form-label h5">Au sema hisia zako:</label>
                                <div class="d-flex gap-2">
                                    <button type="button" id="startVoice" class="btn btn-outline-primary">
                                        🎤 Anza Kurekodi
                                    </button>
                                    <button type="button" id="stopVoice" class="btn btn-outline-danger" style="display:none;">
                                        ⏹️ Acha Kurekodi
                                    </button>
                                </div>
                                <div id="voiceStatus" class="mt-2 text-muted"></div>
                            </div>
                            
                            <input type="hidden" name="mood_type" id="mood_type" value="maandishi">
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    Tathmini Hisia Zangu 🔍
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Data yako inasaidia kujenga maarifa ya jamii huku ukibaki bila kutambuliwa
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