document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.querySelector('.progress-bar');
    const progress = document.querySelector('.progress');
    const form = document.querySelector('form');
    const questions = form.querySelectorAll('.question');
    const totalQuestions = questions.length;
    let answeredQuestions = 0;

    function updateProgress() {
        const percentage = (answeredQuestions / totalQuestions) * 100;
        progress.style.width = `${percentage}%`;
    }

    function saveProgress() {
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_progress.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Progress saved successfully');
            } else {
                console.error('Failed to save progress');
            }
        };
        xhr.send(formData);
    }

    form.addEventListener('change', function(event) {
        if (event.target.closest('.question')) {
            answeredQuestions = Array.from(questions).filter(question => {
                const inputs = question.querySelectorAll('input, textarea, select');
                return Array.from(inputs).some(input => input.value.trim() !== '');
            }).length;
            updateProgress();
            saveProgress();
        }
    });

    function sendReminder() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_reminder.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Reminder sent successfully');
            } else {
                console.error('Failed to send reminder');
            }
        };
        xhr.send();
    }

    setInterval(sendReminder, 24 * 60 * 60 * 1000); // Send reminder every 24 hours
});
