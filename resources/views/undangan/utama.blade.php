<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $landing->groom_name ?? 'Groom' }} & {{ $landing->bride_name ?? 'Bride' }} – Wedding</title>

    <link rel="icon" type="image/jpg" href="images/flavicon.jpg"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Jost:wght@400&family=La+Belle+Aurore&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/imperial-script" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Caveat+Brush&family=Dancing+Script:wght@400;700&family=Eagle+Lake&family=Grey+Qo&family=Poppins:wght@400;600&family=Work+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;600&family=Gilda+Display&family=Niconne&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        'brown-dark': '#321E04',
                        'gold':       '#C9A96E',
                        'brown-mid':  '#7A5C3A',
                        'cream':      '#f5f1eb',
                    },
                    fontFamily: {
                        'cormorant':  ['"Cormorant Garamond"', 'serif'],
                        'jost':       ['Jost', 'sans-serif'],
                        'belle':      ['"La Belle Aurore"', 'cursive'],
                        'imperial':   ['"Imperial Script"', 'cursive'],
                        'grey-qo':    ['"Grey Qo"', 'cursive'],
                        'gilda':      ['"Gilda Display"', 'serif'],
                        'niconne':    ['Niconne', 'cursive'],
                        'greatvibes': ['"Great Vibes"', 'cursive'],
                    },
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/utama.css') }}">
    <script src="{{ asset('js/utama.js') }}" defer></script>

    @php
        $weddingDateJs = $landing->wedding_date
            ? \Carbon\Carbon::parse($landing->wedding_date)->toIso8601String()
            : null;
        $palette = json_decode($landing->palette_colors ?? '[]', true);
        $gallery = json_decode($landing->gallery ?? '[]', true);
        $galleryBySlot = collect($gallery)->keyBy('slot');
        $kegiatan = json_decode($landing->kegiatan ?? '[]', true);
    @endphp

    <script>
        window.weddingDate = @json($weddingDateJs);
    </script>
</head>
<body class="{{ $landing->show_animation ? 'with-animation' : '' }}">

    {{-- ════ HEADER ════ --}}
    <header class="fixed top-0 left-1/2 -translate-x-1/2 w-full px-[70px] py-[10px] flex justify-between items-center z-[1000]"
        style="backdrop-filter: blur(12px); background: rgba(255,255,255,0.2); box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
        <div class="font-['Grey_Qo'] text-[50px] font-extrabold text-[#321E04]"
            style="text-shadow: 2px 2px 5px rgba(0,0,0,0.2);">N</div>
        <nav class="flex gap-[70px]">
            <a href="#home"     class="no-underline text-[20px] text-[#321E04] font-['Cormorant_Garamond']">Home</a>
            <a href="#mempelai" class="no-underline text-[20px] text-[#321E04] font-['Cormorant_Garamond']">Mempelai</a>
            <a href="#acara"    class="no-underline text-[20px] text-[#321E04] font-['Cormorant_Garamond']">Acara</a>
            <a href="#galeri"   class="no-underline text-[20px] text-[#321E04] font-['Cormorant_Garamond']">Galeri</a>
        </nav>
    </header>

    {{-- ════ HERO ════ --}}
    <section id="home" class="h-screen bg-center bg-cover bg-no-repeat flex justify-center items-center"
        style="background-image: url('{{ isset($galleryBySlot['landing']) ? Storage::url($galleryBySlot['landing']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}');">
        <div class="absolute top-[30%] left-[30%] flex flex-col text-white tracking-[0.2em]">
            <h1 class="font-['La_Belle_Aurore'] text-[80px] font-bold text-white text-left"
                style="text-shadow: 2px 2px 10px rgba(0,0,0,0.6);">
                {{ $landing->groom_name ?? 'Groom' }}
            </h1>
            <div class="font-['Imperial_Script'] text-[90px] font-bold text-white text-right -mt-[10px]">&</div>
            <h1 class="font-['La_Belle_Aurore'] text-[80px] font-bold text-white text-right -mt-[10px] translate-x-1/2"
                style="text-shadow: 2px 2px 10px rgba(0,0,0,0.6);">
                {{ $landing->bride_name ?? 'Bride' }}
            </h1>
        </div>
        <p class="absolute font-jost font-semibold text-white text-[16px] tracking-[0.25em] mt-[600px]">
            {{ $landing->wedding_date
                ? \Carbon\Carbon::parse($landing->wedding_date)->format('d F Y')
                : 'Tanggal Belum Ditentukan' }}
        </p>
    </section>

    {{-- ════ MEMPELAI ════ --}}
    <section id="mempelai" class="font-jost flex items-center gap-[60px] px-[80px] pt-[80px] pb-[120px] bg-[#f5f1eb]">
        <div class="relative w-[300px] h-[460px] flex-shrink-0">

            <img src="{{ isset($galleryBySlot['story1']) ? Storage::url($galleryBySlot['story1']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                class="absolute object-cover border-[3px] border-white w-[255px] h-[357px] top-0 left-0 z-[1]">
            <img src="{{ isset($galleryBySlot['story2']) ? Storage::url($galleryBySlot['story2']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                class="absolute object-cover border-[3px] border-white w-[206px] h-[257px] top-[150px] left-[180px] z-[2]">
            <img src="{{ isset($galleryBySlot['ucapan1']) ? Storage::url($galleryBySlot['ucapan1']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                class="absolute object-cover border-[3px] border-white w-[206px] h-[206px] top-[300px] left-[80px] z-[3]">
        </div>
        <div class="ml-[80px]">
            <p class="text-[15px] font-semibold tracking-[0.15em] text-[#321E04] uppercase mb-[20px]">
                THE BEGINNING OF OUR FOREVER
            </p>
            <h2 class="font-gilda text-[#321E04] text-[32px] font-normal uppercase mb-[20px]">
                CELEBRATING LOVE, <br>COMMITMENT, AND A BEAUTIFUL <br>BEGINNING
            </h2>
            <p class="text-[15px] font-light tracking-[0.10em] text-[#321E04] mb-[30px]">
                With love and joy, we invite you to celebrate this special moment with us.
            </p>
            <hr class="border-none h-[1px] bg-[#C9A96E] my-[20px]">
            <p class="font-niconne text-[#321E04] text-[35px] tracking-[0.15em]">
                {{ $landing->groom_name ?? 'Groom' }} & {{ $landing->bride_name ?? 'Bride' }}
            </p>
        </div>
    </section>

    {{-- ════ SAVE THE DATE ════ --}}
    <section id="acara" class="flex justify-between bg-[#321E04] items-stretch gap-[60px]">
        <div class="relative w-1/2 p-[80px] flex-shrink-0">
            <div class="absolute top-[150px] bottom-[150px] left-[80px] right-[80px] z-[3]">
                <div class="corner-tl"></div><div class="corner-tr"></div>
                <div class="corner-bl"></div><div class="corner-br"></div>
            </div>
            <div class="absolute top-[80px] left-[80px] right-[80px] bottom-[80px] p-[40px] z-[2] flex flex-col justify-center text-justify">
                <h2 class="font-gilda text-white text-[50px] tracking-[0.1em]">SAVE</h2>
                <p class="font-niconne text-[#C9A96E] text-[40px] my-[10px]">The</p>
                <h2 class="font-gilda text-white text-[50px] tracking-[0.1em]">DATE</h2>
                <div class="flex flex-col my-[20px]">
                    <span class="w-full h-[1px] mb-[10px]" style="background: linear-gradient(to right, #C9A96E, #7A5C3A);"></span>
                    @if($landing->wedding_date)
                        @php
                            $date = \Carbon\Carbon::parse($landing->wedding_date);
                            $suffix = match((int)$date->format('j')) {
                                1, 21, 31 => 'st', 2, 22 => 'nd', 3, 23 => 'rd', default => 'th'
                            };
                        @endphp
                        <div class="font-[Cormorant_Garamond] text-white text-[25px] inline-block">
                            {{ $date->format('j') }}<span class="text-[15px] align-super">{{ $suffix }}</span>
                            <span class="mx-[32px]">|</span>{{ $date->format('F') }}
                            <span class="mx-[32px]">|</span>{{ $date->format('Y') }}
                        </div>
                    @else
                        <div class="font-[Cormorant_Garamond] text-white text-[25px]">Tanggal Belum Ditentukan</div>
                    @endif
                    <span class="w-full h-[1px] mt-[10px]" style="background: linear-gradient(to right, #C9A96E, #7A5C3A);"></span>
                </div>
                <p class="font-jost font-light text-white text-[15px] tracking-[0.1em] mt-[20px]">
                    With hearts filled with happiness, we invite you to remember this special date and celebrate with us.
                </p>
            </div>
        </div>
        <div class="w-1/2 flex-1 relative self-stretch overflow-hidden m-0 text-[0] leading-[0]">
            <img src="{{ isset($galleryBySlot['save_the_date']) ? Storage::url($galleryBySlot['save_the_date']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                alt="wedding" class="w-full h-full object-cover block"/>
            <div class="absolute left-[-60px] top-0 w-[120px] h-full"
                style="background: linear-gradient(to right, #321E04, transparent);"></div>
        </div>
    </section>

    {{-- ════ VENUE ════ --}}
    <section class="bg-white px-[80px] py-[80px]">
        <div class="flex items-start gap-[48px] mb-[48px]">
            <div class="w-[250px] h-[326px] flex-shrink-0 overflow-hidden rounded-[4px]">
                <img src="{{ isset($galleryBySlot['venue']) ? Storage::url($galleryBySlot['venue']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                    alt="Venue" class="w-full h-full object-cover block"/>
            </div>
            <div class="flex-1 flex flex-col justify-center pl-[20px]">
                <h2 class="font-gilda text-[#321E04] text-[50px] font-bold tracking-[0.10em] leading-none mb-[20px]">THE VENUE</h2>
                <p class="font-jost font-light text-black text-[15px] tracking-[0.10em] leading-[1.75] mb-[28px]">
                    We are delighted to celebrate our special day at a place filled with beauty and warmth.
                </p>
                <p class="font-jost font-semibold text-black text-[15px] tracking-[0.15em] uppercase mb-[12px]">
                    Where Our Special Day Takes Place
                </p>
                <p class="font-jost font-light text-black text-[15px] tracking-[0.15em] leading-[1.85] mb-[22px]">
                    Wedding Ceremony<br>
                    {{ $landing->lokasi_wedding ?? 'Lokasi Belum Ditentukan' }}<br>
                    {{ $landing->kota ?? '' }}
                </p>
                <p class="font-niconne text-[#321E04] text-[30px] font-normal">Save Your Seat</p>
            </div>
        </div>
        @if(!empty($landing->map_iframe))
        <div class="w-[90%] mx-auto overflow-hidden rounded-[6px] mb-[20px] leading-[0] [&>iframe]:w-full [&>iframe]:h-[376px] [&>iframe]:block"
            style="border: 5px solid #321E04;">
            {!! $landing->map_iframe !!}
        </div>
        <a href="https://maps.google.com/?q={{ urlencode(($landing->lokasi_wedding ?? '') . ' ' . ($landing->kota ?? '')) }}"
            target="_blank" rel="noopener noreferrer"
            class="block text-center font-jost font-semibold text-[#321E04] text-[15px] tracking-[0.15em] uppercase no-underline pt-[12px] pb-[4px] hover:underline underline-offset-4">
            Open on Google Maps
        </a>
        @endif
    </section>

    {{-- ════ COUNTDOWN ════ --}}
    <section class="bg-[#321E04] px-[40px] pt-[60px] pb-[70px] text-center">
        <p class="font-niconne text-white text-[60px] font-normal tracking-[0.05em] mb-[40px] leading-none">
            Until our wedding day
        </p>
        <div class="flex items-start justify-center">
            <div class="flex flex-col items-center min-w-[140px]">
                <span class="font-gilda text-white text-[80px] font-normal tracking-[0.05em] leading-none" id="cd-days">000</span>
                <span class="font-gilda text-white text-[25px] font-normal tracking-[0.10em] mt-[10px] leading-none">Days</span>
            </div>
            <span class="font-gilda text-[#C9A96E] text-[80px] font-normal leading-none px-[4px] self-start">:</span>
            <div class="flex flex-col items-center min-w-[140px]">
                <span class="font-gilda text-white text-[80px] font-normal tracking-[0.05em] leading-none" id="cd-hours">00</span>
                <span class="font-gilda text-white text-[25px] font-normal tracking-[0.10em] mt-[10px] leading-none">Hours</span>
            </div>
            <span class="font-gilda text-[#C9A96E] text-[80px] font-normal leading-none px-[4px] self-start">:</span>
            <div class="flex flex-col items-center min-w-[140px]">
                <span class="font-gilda text-white text-[80px] font-normal tracking-[0.05em] leading-none" id="cd-minutes">00</span>
                <span class="font-gilda text-white text-[25px] font-normal tracking-[0.10em] mt-[10px] leading-none">Minutes</span>
            </div>
            <span class="font-gilda text-[#C9A96E] text-[80px] font-normal leading-none px-[4px] self-start">:</span>
            <div class="flex flex-col items-center min-w-[140px]">
                <span class="font-gilda text-white text-[80px] font-normal tracking-[0.05em] leading-none" id="cd-seconds">00</span>
                <span class="font-gilda text-white text-[25px] font-normal tracking-[0.10em] mt-[10px] leading-none">Seconds</span>
            </div>
        </div>
    </section>

    {{-- ════ TIMELINE (dari kegiatan database) ════ --}}
    <section class="w-full bg-[#f5f1eb] overflow-hidden leading-none">
        <svg id="tl-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"
            width="100%" preserveAspectRatio="xMidYMid meet" class="block">
            <path id="tl-path"
                d="M 80,0
                C 80,40   120,60   180,75
                C 260,95  220,280  280,280
                C 340,280 400,100  460,130
                C 530,165 540,340  600,360
                C 665,382 700,155  740,180
                C 790,210 840,380  880,400
                C 930,425 960,190  1000,220
                C 1050,258 1060,460 1080,490"/>

            @php
                $tlPositions = [
                    ['x'=>180,'y'=>83,  'tx'=>220,'ty'=>70,  'anchor'=>'start'],
                    ['x'=>280,'y'=>288, 'tx'=>226,'ty'=>280, 'anchor'=>'end'],
                    ['x'=>450,'y'=>137, 'tx'=>490,'ty'=>124, 'anchor'=>'start'],
                    ['x'=>610,'y'=>370, 'tx'=>635,'ty'=>400, 'anchor'=>'end'],
                    ['x'=>735,'y'=>190, 'tx'=>775,'ty'=>174, 'anchor'=>'start'],
                    ['x'=>885,'y'=>410, 'tx'=>845,'ty'=>400, 'anchor'=>'end'],
                    ['x'=>995,'y'=>225, 'tx'=>1035,'ty'=>214,'anchor'=>'start'],
                    ['x'=>1078,'y'=>495,'tx'=>1038,'ty'=>490,'anchor'=>'end'],
                ];
                $delays = [200,580,950,1280,1580,1880,2150,2420];
            @endphp

            @foreach($kegiatan as $i => $item)
                @if($i >= 8) @break @endif
                @php $pos = $tlPositions[$i]; @endphp
                <g class="ev-group" data-delay="{{ $delays[$i] }}">
                    <text class="ev-heart" x="{{ $pos['x'] }}" y="{{ $pos['y'] }}" text-anchor="middle">♥</text>
                    <text class="ev-time"  id="ev-time-{{ $i }}"  x="{{ $pos['tx'] }}" y="{{ $pos['ty'] }}"  text-anchor="{{ $pos['anchor'] }}">
                        {{ $item['time'] ?? '' }} {{ $item['period'] ?? '' }}
                    </text>
                    <text class="ev-label" id="ev-label-{{ $i }}" x="{{ $pos['tx'] }}" y="{{ $pos['ty'] + 15 }}" text-anchor="{{ $pos['anchor'] }}">
                        {{ $item['name'] ?? '' }}
                    </text>
                </g>
            @endforeach

            @if(empty($kegiatan))
                {{-- fallback hardcoded jika belum ada data --}}
                <g class="ev-group" data-delay="200">
                    <text class="ev-heart" x="180" y="83" text-anchor="middle">♥</text>
                    <text class="ev-time"  x="220" y="70"  text-anchor="start">09:00 AM</text>
                    <text class="ev-label" x="220" y="85"  text-anchor="start">Guest Arrival</text>
                </g>
            @endif

            <text id="tl-title" class="tl-heading" x="60" y="500">Our Timeline</text>
        </svg>
    </section>

    {{-- ════ DRESS CODE ════ --}}
    <section class="relative w-full h-screen overflow-hidden"
        style="display:grid; grid-template-columns:320px 220px 1fr; grid-template-rows:50vh 50vh; gap:2px; background:#FFFFFF;">

        <div class="relative overflow-hidden" style="grid-column:1; grid-row:1/3;">
            <div class="absolute top-0 bottom-0 w-px z-10" style="left:40px; background:#FFFFFF;"></div>
            <div class="absolute inset-x-0 z-10" style="top:50%; height:2px; background:#FFFFFF;"></div>
            <img src="{{ isset($galleryBySlot['dresscode1']) ? Storage::url($galleryBySlot['dresscode1']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                class="w-full h-full object-cover"/>
        </div>

        <div class="overflow-hidden" style="grid-column:2; grid-row:1;">
            <img src="{{ isset($galleryBySlot['dresscode2']) ? Storage::url($galleryBySlot['dresscode2']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                class="w-full h-full object-cover"/>
        </div>

        <div class="flex flex-col justify-start relative overflow-hidden bg-[#3B2710]"
            style="grid-column:3; grid-row:1; padding:5vh 3vw;">
            <p class="font-jost font-semibold text-[#7A5C3A] text-[15px] tracking-[0.15em] uppercase">
                Kindly Dress in Formal Attire
            </p>
            <span class="font-['Great_Vibes'] font-light text-[#F5ECD7] tracking-[0.05em] mt-4 block"
                style="font-size: clamp(40px, 4.5vw, 80px);">Dress Code</span>
            <p class="font-jost font-light text-white tracking-[0.10em] leading-[1.7] mt-4 mb-7 w-full"
                style="font-size: clamp(14px, 2.5vw, 20px);">
                {{ $landing->dresscode_text ?? 'To create a harmonious and elegant celebration, we kindly invite our guests to wear formal attire in the following color palette.' }}
            </p>

            {{-- Palette dari database --}}
            <div class="flex gap-3 justify-center">
                @if(!empty($palette))
                    @foreach($palette as $color)
                    <div class="w-[22px] h-[22px] rounded-[4px] p-[1.5px]"
                        style="background: linear-gradient(135deg, #F5D6AD, #8F7D65);">
                        <div class="w-full h-full rounded-[3px]" style="background:{{ $color }};"></div>
                    </div>
                    @endforeach
                @else
                    {{-- fallback default palette --}}
                    @foreach(['#FFFFFF','#563E32','#5C5C38','#36161B','#151A20'] as $color)
                    <div class="w-[22px] h-[22px] rounded-[4px] p-[1.5px]"
                        style="background: linear-gradient(135deg, #F5D6AD, #8F7D65);">
                        <div class="w-full h-full rounded-[3px]" style="background:{{ $color }};"></div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="overflow-hidden" style="grid-column:2; grid-row:2;">
            <img src="{{ isset($galleryBySlot['dresscode3']) ? Storage::url($galleryBySlot['dresscode3']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                class="w-full h-full object-cover"/>
        </div>
        <div class="overflow-hidden" style="grid-column:3; grid-row:2;">
            <img src="{{ isset($galleryBySlot['dresscode4']) ? Storage::url($galleryBySlot['dresscode4']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                class="w-full h-full object-cover object-top"/>
        </div>
    </section>

    {{-- ════ STORY OF US ════ --}}
    <section class="bg-[#f5f3ee] min-h-screen">
        <div class="flex flex-row items-start gap-16 max-w-[860px] w-full mx-auto px-[60px] py-[80px]">
            <div class="flex flex-col flex-1">
                <h1 class="font-gilda font-normal text-[#321E04] text-[50px] tracking-10 mb-[30px] leading-tight">
                    STORY OF US
                </h1>
                <div class="mb-[30px]">
                    <h2 class="font-jost font-normal uppercase text-[#321E04] text-[20px] tracking-15 text-center mb-[30px]">
                        HOW WE MET
                    </h2>
                    <p class="font-jost font-light text-[#321E04] text-[15px] tracking-10 leading-relaxed text-center">
                        {{ $landing->cerita_bertemu ?? 'Our story began in the most unexpected way — through a simple introduction during our university days.' }}
                    </p>
                </div>
                <div>
                    <h2 class="font-jost font-normal uppercase text-[#321E04] text-[20px] tracking-15 text-center mb-[30px]">
                        HOW WE PROPOSAL
                    </h2>
                    <p class="font-jost font-light text-[#321E04] text-[15px] tracking-10 leading-relaxed text-center">
                        {{ $landing->cerita_melamar ?? 'As time passed, it became clear that our journey together was meant to last a lifetime.' }}
                    </p>
                </div>
            </div>
            <div class="flex flex-col flex-shrink-0 gap-[30px]">
                <div class="w-[275px] h-[275px] overflow-hidden">
                    <img src="{{ isset($galleryBySlot['story1']) ? Storage::url($galleryBySlot['story1']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                        alt="Story 1" class="object-cover w-full h-full"/>
                </div>
                <div class="w-[275px] h-[275px] overflow-hidden">
                    <img src="{{ isset($galleryBySlot['story2']) ? Storage::url($galleryBySlot['story2']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                        alt="Story 2" class="object-cover w-full h-full"/>
                </div>
            </div>
        </div>
    </section>

    {{-- ════ GALERI ════ --}}
    <section id="galeri" class="bg-[#321E04] flex flex-col items-center px-[60px] py-[60px]">
        <h1 class="font-niconne text-white text-[60px] font-normal tracking-5 mb-[50px]">Gallery</h1>
        <div class="flex flex-row items-end justify-center gap-[30px] w-full max-w-[900px]">

            {{-- Orang tua mempelai pria --}}
            <div class="flex flex-col items-center gap-0">
                <div class="flex flex-row gap-[20px] mb-[24px]">
                    <div class="arch-wrapper">
                        <img src="{{ isset($galleryBySlot['ayah_pria']) ? Storage::url($galleryBySlot['ayah_pria']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}" alt="Ayah Mempelai Pria"/>
                    </div>
                    <div class="arch-wrapper">
                        <img src="{{ isset($galleryBySlot['ibu_pria']) ? Storage::url($galleryBySlot['ibu_pria']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}" alt="Ibu Mempelai Pria"/>
                    </div>
                </div>
                <div class="text-center">
                    <p class="font-jost font-normal text-white text-[24px] tracking-10">
                        {{ $landing->ayah_pria ? 'Bpk. ' . $landing->ayah_pria : 'Mr. &amp; Mrs. —' }}
                        @if($landing->ibu_pria) & Ibu {{ $landing->ibu_pria }} @endif
                    </p>
                    <p class="font-jost font-normal text-[#E0A96A] text-[18px] tracking-15 uppercase mt-[4px]">Parents of the Groom</p>
                </div>
            </div>

            {{-- Orang tua mempelai wanita --}}
            <div class="flex flex-col items-center gap-0">
                <div class="flex flex-row gap-[20px] mb-[24px]">
                    <div class="arch-wrapper">
                        <img src="{{ isset($galleryBySlot['ayah_wanita']) ? Storage::url($galleryBySlot['ayah_wanita']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}" alt="Ayah Mempelai Wanita"/>
                    </div>
                    <div class="arch-wrapper">
                        <img src="{{ isset($galleryBySlot['ibu_wanita']) ? Storage::url($galleryBySlot['ibu_wanita']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}" alt="Ibu Mempelai Wanita"/>
                    </div>
                </div>
                <div class="text-center">
                    <p class="font-jost font-normal text-white text-[24px] tracking-10">
                        {{ $landing->ayah_wanita ? 'Bpk. ' . $landing->ayah_wanita : 'Mr. &amp; Mrs. —' }}
                        @if($landing->ibu_wanita) & Ibu {{ $landing->ibu_wanita }} @endif
                    </p>
                    <p class="font-jost font-normal text-[#E0A96A] text-[18px] tracking-15 uppercase mt-[4px]">Parents of the Bride</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ════ SCROLL GALLERY ════ --}}
    <div class="relative w-full" style="background:#321E04;">
        <div class="absolute z-10"
            style="width:100%; height:18%; background:#321E04; border-radius:0 0 50% 50%; top:0;"></div>
        <div class="relative z-0 w-full overflow-x-auto overflow-y-hidden py-8 px-6">
            <div class="flex gap-4" style="width: max-content;">
                @php $galeriItems = collect($gallery)->where('slot', 'galeri')->values(); @endphp
                @if($galeriItems->count())
                    @foreach($galeriItems as $img)
                    <div style="width:220px; height:320px; flex-shrink:0; overflow:hidden;">
                        <img src="{{ Storage::url($img['path']) }}" class="w-full h-full object-cover"/>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="absolute z-10"
            style="width:100%; height:18%; background:#321E04; border-radius:50% 50% 0 0; bottom:0;"></div>
    </div>

    {{-- ════ RSVP ════ --}}
    <section class="bg-[#F8F8F8] flex flex-col items-center px-[60px] py-[60px]">
        <p class="font-jost font-semibold text-[#321E04] text-[20px] tracking-[0.15em] uppercase mb-3">
            Confirmation of Attendance
        </p>
        <h1 class="font-greatvibes font-normal text-[#321E04] text-[70px] tracking-[0.20em] mb-10">
            Will you join us?
        </h1>
        <form id="rsvp-form" class="w-full max-w-[640px] flex flex-col gap-6">
            <input type="hidden" name="guest_id" id="guest-id" value="{{ $guest->id ?? '' }}" />
            <div class="flex flex-col gap-2">
                <label class="font-jost font-semibold text-[#321E04] text-[18px] tracking-[0.25em] uppercase">Your Name</label>
                <div style="background: linear-gradient(135deg, #8F7D65, #635336); padding: 1px; border-radius: 10px;">
                    <input type="text" name="name" placeholder="Enter your name" required
                        class="w-full px-5 py-4 text-[#321E04] placeholder-[#9e8e7a] outline-none"
                        style="font-family:'Roboto',sans-serif;background:#ffffff;border-radius:9px;border:none;width:100%;letter-spacing:0.1em;"
                        value="{{ $guest->nama ?? '' }}"
                        @if(isset($guest) && $guest->nama) readonly @endif>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-jost font-semibold text-[#321E04] text-[18px] tracking-[0.25em] uppercase">Confirm Your Attendance</label>
                <div class="flex gap-4">
                    <button type="button" onclick="selectAttendance('present', this)"
                        class="attendance-btn flex-1 py-4 font-jost font-normal text-[#321E04] text-[14px] tracking-[0.15em] uppercase rounded-md border border-[#8F7D65] bg-transparent transition-all duration-300 hover:bg-green-500">
                        Present
                    </button>
                    <button type="button" onclick="selectAttendance('not_present', this)"
                        class="attendance-btn flex-1 py-4 font-jost font-normal text-[#321E04] text-[14px] tracking-[0.15em] uppercase rounded-md border border-[#8F7D65] bg-transparent transition-all duration-300 hover:bg-red-500">
                        Not Present
                    </button>
                </div>
                <input type="hidden" name="attendance" id="attendance-val">
            </div>
            <input type="hidden" name="category" id="category-val">
            <input type="hidden" name="pax" id="pax-val">
            <div class="flex flex-col gap-2">
                <label class="font-jost font-semibold text-[#321E04] text-[18px] tracking-[0.25em] uppercase">Category</label>
                <div class="flex gap-4">
                    <button type="button" onclick="selectCategory('family', this)"
                        class="category-btn flex-1 py-4 font-jost font-normal text-[#321E04] text-[14px] tracking-[0.15em] uppercase rounded-md border border-[#8F7D65] bg-transparent transition-all duration-300 hover:bg-[#8F7D65]/20">
                        Family (4 Pax)
                    </button>
                    <button type="button" onclick="selectCategory('friends', this)"
                        class="category-btn flex-1 py-4 font-jost font-normal text-[#321E04] text-[14px] tracking-[0.15em] uppercase rounded-md border border-[#8F7D65] bg-transparent transition-all duration-300 hover:bg-[#8F7D65]/20">
                        Friends (2 Pax)
                    </button>
                </div>
                <p id="pax-display" class="font-jost text-[14px] text-[#321E04] mt-1 hidden">
                    Quota: <span id="pax-count" class="font-bold">-</span> Pax
                </p>
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-jost font-semibold text-[#321E04] text-[18px] tracking-[0.25em] uppercase">Message</label>
                <div style="background: linear-gradient(135deg, #8F7D65, #635336); padding: 1px; border-radius: 10px;">
                    <textarea name="message" placeholder="Enter your messages" rows="3"
                        class="w-full px-5 py-4 text-[#321E04] placeholder-[#9e8e7a] outline-none resize-none"
                        style="font-family:'Roboto',sans-serif;background:#ffffff;border-radius:9px;border:none;width:100%;display:block;letter-spacing:0.1em;"></textarea>
                </div>
            </div>
            <button type="submit"
                class="w-full py-5 font-jost font-normal text-[#321E04] text-[14px] tracking-[0.15em] uppercase rounded-md border border-[#8F7D65] bg-transparent transition-all duration-300 hover:bg-[#321E04] hover:text-white hover:border-[#321E04]">
                Send Confirmation
            </button>
            <p id="form-status" class="text-center text-sm font-jost hidden"></p>
        </form>
    </section>

    {{-- ════ THANK YOU ════ --}}
    <section class="bg-[#F8F8F8] flex items-center justify-center py-16 px-10">
        <div class="flex items-end justify-center gap-0" style="max-width:1200px;width:100%;">
            <div class="relative flex-shrink-0" style="width:520px;">
                <img src="{{ isset($galleryBySlot['penutup']) ? Storage::url($galleryBySlot['penutup']['path']) : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}"
                    alt="Couple" class="w-full h-full object-cover block"
                    style="box-shadow: 25px 25px 0px 0px #321E04;">
            </div>
            <div class="flex flex-col justify-center px-14"
                style="background:#D9CFC7;gap:30px;height:450px;flex:1;">
                <div class="flex items-center gap-4">
                    <p class="font-jost font-semibold uppercase text-[#321E04]"
                        style="font-size:15px;letter-spacing:0.25em;white-space:nowrap;">Thank You</p>
                    <div style="width:80px;height:1px;background:#C9A96E;flex-shrink:0;"></div>
                </div>
                <h2 class="font-gilda font-normal uppercase text-[#321E04]" style="font-size:36px;line-height:1.2;">
                    Thank you for being part of our special day.
                </h2>
                <p class="font-jost font-normal text-[#321E04]" style="font-size:15px;letter-spacing:0.10em;line-height:1.8;">
                    Thank you for your love, prayers, and warm wishes. Your presence means the world to us as we celebrate this beautiful beginning together.
                </p>
            </div>
        </div>
    </section>

    {{-- ════ FOOTER ════ --}}
    <footer class="w-full px-16 py-10" style="background:#321E04;">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <span class="font-grey-qo text-white pr-4" style="font-size:48px;line-height:1;font-weight:600;">N</span>
                <div style="width:2px;height:100px;background:rgba(255,255,255,0.3);"></div>
                <div class="flex flex-col gap-1 pl-4">
                    <p class="font-jost font-normal text-white" style="font-size:18px;letter-spacing:0.1em;">
                        A joyful celebration of love and togetherness
                    </p>
                    <p class="font-jost font-normal text-white" style="font-size:18px;letter-spacing:0.1em;">
                        © {{ date('Y') }} Wedding Invitation
                    </p>
                    <p class="font-jost font-normal text-white" style="font-size:18px;letter-spacing:0.1em;">Made for love</p>
                </div>
            </div>
            <div class="flex flex-col items-center gap-3">
                <div class="flex items-center gap-4">
                    <a href="#" class="text-white hover:opacity-70 transition-opacity"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-white hover:opacity-70 transition-opacity"><i class="fab fa-x-twitter text-xl"></i></a>
                    <a href="#" class="text-white hover:opacity-70 transition-opacity"><i class="fab fa-tiktok text-xl"></i></a>
                    <a href="#" class="text-white hover:opacity-70 transition-opacity"><i class="fab fa-facebook text-xl"></i></a>
                    <a href="#" class="text-white hover:opacity-70 transition-opacity"><i class="fab fa-youtube text-xl"></i></a>
                </div>
                <p class="font-jost font-semibold text-white uppercase" style="font-size:13px;letter-spacing:0.2em;">Contact Us</p>
            </div>
        </div>
    </footer>

    {{-- Musik --}}
    <button id="musicToggle" class="music-btn">
        <i id="musicIcon" class="fas fa-play"></i>
    </button>
    <audio id="bgMusic" loop {{ $landing->play_music ? 'autoplay' : '' }}>
        <source src="music/wedding-music1.mp3" type="audio/mpeg">
    </audio>

</body>
</html>