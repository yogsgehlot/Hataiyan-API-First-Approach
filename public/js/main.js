$(document).ready(function(){
    const storedTheme = localStorage.getItem('theme') || 'system';
    setTheme(storedTheme);

    $('#themeToggle').click(function() {
        let current = document.documentElement.getAttribute('data-bs-theme');
        let next = current === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', next);
        localStorage.setItem('theme', next);
    });

    function setTheme(theme) {
        if (theme === 'system') {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-bs-theme', prefersDark ? 'dark' : 'light');
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme);
        }
    }
});
