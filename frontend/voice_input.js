document.addEventListener('DOMContentLoaded', function() {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = 'en-US';
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    const voiceInputButtons = document.querySelectorAll('.voice-input-button');
    voiceInputButtons.forEach(button => {
        button.addEventListener('click', function() {
            const inputField = document.querySelector(`#${this.dataset.input}`);
            recognition.start();

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                inputField.value = transcript;
            };

            recognition.onspeechend = function() {
                recognition.stop();
            };

            recognition.onerror = function(event) {
                console.error('Speech recognition error:', event.error);
            };
        });
    });
});
