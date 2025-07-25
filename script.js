// SentiLink AI Frontend JavaScript

document.addEventListener('DOMContentLoaded', function() {
    const startVoiceBtn = document.getElementById('startVoice');
    const stopVoiceBtn = document.getElementById('stopVoice');
    const voiceStatus = document.getElementById('voiceStatus');
    const moodTextArea = document.getElementById('mood_text');
    const moodTypeInput = document.getElementById('mood_type');
    const emojiInputs = document.querySelectorAll('input[name="mood_emoji"]');
    
    let recognition = null;
    let isRecording = false;

    // Check for Web Speech API support
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SpeechRecognition();
        
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'en-US';
        
        recognition.onstart = function() {
            isRecording = true;
            startVoiceBtn.style.display = 'none';
            stopVoiceBtn.style.display = 'inline-block';
            voiceStatus.textContent = 'Listening... Speak now!';
            voiceStatus.className = 'mt-2 text-primary recording';
        };
        
        recognition.onresult = function(event) {
            let finalTranscript = '';
            let interimTranscript = '';
            
            for (let i = event.resultIndex; i < event.results.length; i++) {
                const transcript = event.results[i][0].transcript;
                if (event.results[i].isFinal) {
                    finalTranscript += transcript;
                } else {
                    interimTranscript += transcript;
                }
            }
            
            moodTextArea.value = finalTranscript;
            voiceStatus.textContent = 'Heard: ' + (finalTranscript || interimTranscript);
            
            if (finalTranscript) {
                moodTypeInput.value = 'voice';
                // Clear emoji selections when voice is used
                emojiInputs.forEach(input => input.checked = false);
            }
        };
        
        recognition.onerror = function(event) {
            voiceStatus.textContent = 'Error: ' + event.error;
            voiceStatus.className = 'mt-2 text-danger';
            stopRecording();
        };
        
        recognition.onend = function() {
            stopRecording();
        };
        
    } else {
        startVoiceBtn.style.display = 'none';
        voiceStatus.textContent = 'Voice input not supported in this browser';
        voiceStatus.className = 'mt-2 text-warning';
    }
    
    // Voice recording controls
    startVoiceBtn.addEventListener('click', function() {
        if (recognition && !isRecording) {
            recognition.start();
        }
    });
    
    stopVoiceBtn.addEventListener('click', function() {
        if (recognition && isRecording) {
            recognition.stop();
        }
    });
    
    function stopRecording() {
        isRecording = false;
        startVoiceBtn.style.display = 'inline-block';
        stopVoiceBtn.style.display = 'none';
        voiceStatus.className = 'mt-2 text-muted';
        if (moodTextArea.value.trim() === '') {
            voiceStatus.textContent = '';
        }
    }
    
    // Handle emoji selection
    emojiInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                moodTypeInput.value = 'emoji';
                moodTextArea.value = ''; // Clear text when emoji is selected
                voiceStatus.textContent = '';
            }
        });
    });
    
    // Handle text input
    moodTextArea.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            moodTypeInput.value = 'text';
            // Clear emoji selections when text is entered
            emojiInputs.forEach(input => input.checked = false);
        }
    });
    
    // Form validation
    document.getElementById('moodForm').addEventListener('submit', function(e) {
        const hasEmoji = Array.from(emojiInputs).some(input => input.checked);
        const hasText = moodTextArea.value.trim() !== '';
        
        if (!hasEmoji && !hasText) {
            e.preventDefault();
            alert('Please select an emoji or enter text to describe your mood!');
            return false;
        }
        
        // Add loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Analyzing... 🔄';
    });
    
    // Add smooth animations
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Emoji hover effects
    const emojiLabels = document.querySelectorAll('.emoji-label');
    emojiLabels.forEach(label => {
        label.addEventListener('mouseenter', function() {
            if (!this.previousElementSibling.checked) {
                this.style.transform = 'scale(1.1)';
            }
        });
        
        label.addEventListener('mouseleave', function() {
            if (!this.previousElementSibling.checked) {
                this.style.transform = 'scale(1)';
            }
        });
    });
});

// Utility functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Analytics tracking (placeholder)
function trackMoodSubmission(moodType, moodCategory) {
    console.log(`Mood submitted: ${moodType} - ${moodCategory}`);
    // Add your analytics tracking code here
}