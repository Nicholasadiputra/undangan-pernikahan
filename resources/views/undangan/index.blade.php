<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($landing->groom_name ?? 'Groom') }} & {{ ($landing->bride_name ?? 'Bride') }} – Wedding Invitation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <link rel="icon" type="image/jpg" href="{{ asset('favicon.jpg') }}">
</head>
<body>

<!-- HERO -->
<section class="hero" id="top">
    <div class="hero__bg-placeholder"></div>
    <img class="hero__bg" src="images/hero-bg.jpg" alt="{{ ($landing->groom_name ?? 'Groom') }} & {{ ($landing->bride_name ?? 'Bride') }}" onerror="this.style.display='none'"/>
    <div class="hero__overlay"></div>
    <div class="hero__content">
        <div class="hero__names">
            <span class="hero__name">{{ $landing->groom_name ?? 'Groom' }}</span>
            <span class="hero__amp">&amp;</span>
            <span class="hero__name">{{ $landing->bride_name ?? 'Bride' }}</span>
        </div>
        <div class="hero__card">
            <p class="hero__card-text">Together with our families, we invite you to<br>share in the joy of our wedding celebration.</p>
        </div>
    </div>
</section>

<!-- INVITATION -->
<section class="invitation">
    <div class="invitation__inner">
        <div class="invitation__photo reveal">
            <img src="images/foto1.jpg" alt="Couple" onerror="this.style.display='none';document.getElementById('pFallback').style.display='flex'"/>
            <div class="invitation__photo-placeholder" id="pFallback" style="display:none;">foto2.jpg</div>
        </div>
        <div class="invitation__text">
            <div class="inv-label reveal" style="transition-delay:.07s">Wedding Invitation</div>
            <h2 class="inv-heading reveal" style="transition-delay:.13s">Hey, {{ request('to', 'Tamu Undangan') }}</h2>
            <p class="inv-body reveal">Welcome to our wedding invitation page. We would be honored to have your presence and blessings on our special day.</p>
            <div class="inv-ornament reveal" style="transition-delay:.25s">
                <span class="inv-ornament__dot"></span>
                <span class="inv-ornament__line"></span>
                <span class="inv-ornament__diamond">◆</span>
                <span class="inv-ornament__line"></span>
                <span class="inv-ornament__dot"></span>
            </div>
            <p class="inv-tagline reveal">Our Love Story Awaits</p>
        </div>
    </div>
</section>

<!-- TOMBOL MASUK (ganti form login lama) -->
<section class="login-section" id="login">
    <div class="login__bg-placeholder"></div>
    <img class="login__bg" src="images/background-landing.jpg" alt="Wedding venue" onerror="this.style.display='none'"/>
    <div class="login__overlay"></div>
    <div class="login__glass">
        <h2 class="login__title">Buka Undangan</h2>
        <p style="text-align:center; margin-bottom:20px; color:#fff;">Klik tombol di bawah untuk membuka undangan</p>
        <form method="POST" action="{{ route('tamu.masuk') }}">
            @csrf
            <button type="submit" class="login__btn">Masuk sebagai Tamu</button>
        </form>
    </div>
</section>

<button id="musicToggle" class="music-btn">
    <i id="musicIcon" class="fas fa-play"></i>
</button>
<audio id="bgMusic" loop>
    <source src="music/wedding-music1.mp3" type="audio/mpeg">
</audio>

</body>
</html>