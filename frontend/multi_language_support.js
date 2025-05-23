document.addEventListener('DOMContentLoaded', function() {
    const languageSelector = document.getElementById('language-selector');
    const supportedLanguages = ['en', 'es', 'fr', 'de', 'zh'];

    languageSelector.addEventListener('change', function() {
        const selectedLanguage = languageSelector.value;
        if (supportedLanguages.includes(selectedLanguage)) {
            loadLanguage(selectedLanguage);
        }
    });

    function loadLanguage(language) {
        fetch(`languages/${language}.json`)
            .then(response => response.json())
            .then(translations => {
                applyTranslations(translations);
            })
            .catch(error => {
                console.error('Error loading language file:', error);
            });
    }

    function applyTranslations(translations) {
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.getAttribute('data-translate');
            if (translations[key]) {
                element.textContent = translations[key];
            }
        });
    }
});
