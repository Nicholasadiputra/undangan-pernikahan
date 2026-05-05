// 1. Smooth Scroll ke #login
document.querySelectorAll('a[href="#login"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        document.getElementById('login')?.scrollIntoView({ behavior: 'smooth' });
    });
});

// 2. Kontrol musik
const music     = document.getElementById('bgMusic');
const musicBtn  = document.getElementById('musicToggle');
const musicIcon = document.getElementById('musicIcon');

if (musicBtn && music) {
    musicBtn.addEventListener('click', () => {
        if (music.paused) {
            music.play();
            musicIcon.classList.replace('fa-play', 'fa-pause');
            musicBtn.classList.add('playing');
            sessionStorage.setItem('musicPlaying', 'true');
        } else {
            music.pause();
            musicIcon.classList.replace('fa-pause', 'fa-play');
            musicBtn.classList.remove('playing');
            sessionStorage.setItem('musicPlaying', 'false');
        }
    });
}

// 3. Scroll Reveal
const revealObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
    });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));