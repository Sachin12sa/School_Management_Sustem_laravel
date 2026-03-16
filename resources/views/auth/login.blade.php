<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Brain Fart Institute — Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,600&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink: #0a0e1a; --ink2: #141929;
            --gold: #d4a843; --gold2: #f0c96a; --gold3: #a07828;
            --sky: #1a2744; --sky2: #243258;
            --frost: rgba(255,255,255,0.06); --frostr: rgba(255,255,255,0.12);
            --cream: #fdf8ed; --cream2: #f5edda;
            --ruby: #c0392b; --jade: #1d9e75;
        }
        html, body { height: 100%; font-family: 'Syne', sans-serif; background: var(--ink); overflow-x: hidden; }
        
        .page {
            min-height: 100vh; display: grid; grid-template-columns: 1fr 440px;
            position: relative; overflow: hidden;
        }

        /* ── ANIMATED STARFIELD BACKGROUND ─────────────────────────────── */
        .stars { position: absolute; inset: 0; pointer-events: none; z-index: 0; }
        .star { position: absolute; border-radius: 50%; background: #fff; animation: twinkle var(--d,3s) var(--delay,0s) ease-in-out infinite; }
        @keyframes twinkle { 0%, 100% { opacity: 0.15; transform: scale(1); } 50% { opacity: var(--op,0.7); transform: scale(1.4); } }

        /* ── AURORA ORBS ─────────────────────────────────────────────────── */
        .aurora { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; animation: drift var(--ds,12s) ease-in-out infinite alternate; }
        @keyframes drift { 0% { transform: translate(0,0) scale(1); } 100% { transform: translate(var(--dx,30px),var(--dy,20px)) scale(1.1); } }
        .orb1 { width: 500px; height: 500px; top: -100px; left: -100px; background: rgba(100,60,180,0.22); --ds: 14s; --dx: 40px; --dy: 30px; }
        .orb2 { width: 400px; height: 400px; top: 200px; left: 200px; background: rgba(20,100,200,0.18); --ds: 10s; --dx: -30px; --dy: 40px; }
        .orb3 { width: 350px; height: 350px; bottom: -80px; right: 200px; background: rgba(212,168,67,0.12); --ds: 16s; --dx: 20px; --dy: -30px; }

        /* ── HERO SIDE ───────────────────────────────────────────────────── */
        .hero {
            position: relative; z-index: 1;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            padding: 4rem 3.5rem; text-align: center;
        }
        .crest-ring {
            position: relative; width: 180px; height: 180px; margin-bottom: 3rem;
            animation: floatY 5s ease-in-out infinite;
        }
        @keyframes floatY { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
        .ring-outer {
            position: absolute; inset: 0; border-radius: 50%;
            border: 1px solid rgba(212,168,67,0.35);
            animation: rotateSlow 20s linear infinite;
        }
        @keyframes rotateSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .ring-inner {
            position: absolute; inset: 10px; border-radius: 50%;
            border: 1px dashed rgba(212,168,67,0.2);
            animation: rotateSlow 14s linear infinite reverse;
        }
        .ring-dots { position: absolute; inset: 0; }
        .dot-orbit {
            position: absolute; width: 8px; height: 8px; border-radius: 50%;
            background: var(--gold); top: 50%; left: 50%; margin: -4px;
            animation: orbitDot var(--od,8s) linear infinite;
            transform-origin: 0 0;
        }
        @keyframes orbitDot { from { transform: rotate(var(--start,0deg)) translateX(86px); } to { transform: rotate(calc(var(--start,0deg) + 360deg)) translateX(86px); } }
        .crest-core {
            position: absolute; inset: 20px; border-radius: 50%;
            background: rgba(212,168,67,0.08); border: 1px solid rgba(212,168,67,0.25);
            display: flex; align-items: center; justify-content: center;
            flex-direction: column; gap: 2px;
        }
        .crest-letters { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--gold2); letter-spacing: 2px; line-height: 1; }
        .crest-sub { font-size: 9px; letter-spacing: 3px; text-transform: uppercase; color: rgba(212,168,67,0.55); }

        .hero-tag { font-size: 10px; letter-spacing: 3px; text-transform: uppercase; color: var(--gold3); margin-bottom: .75rem; }
        .hero-h1 { font-family: 'Playfair Display', serif; font-size: clamp(2rem,3vw,2.8rem); color: #fff; line-height: 1.15; margin-bottom: .6rem; }
        .hero-h1 em { color: var(--gold2); font-style: italic; }
        .hero-rule { width: 100px; height: 1px; margin: .9rem auto; position: relative; }
        .hero-rule::before, .hero-rule::after { content: ''; position: absolute; top: 0; height: 1px; background: var(--gold3); }
        .hero-rule::before { left: 0; width: 40px; } .hero-rule::after { right: 0; width: 40px; }
        .hero-rule span { position: absolute; left: 50%; top: -3px; transform: translateX(-50%); width: 6px; height: 6px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 8px var(--gold); }
        .hero-motto { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1rem; color: rgba(255,255,255,0.4); max-width: 300px; line-height: 1.65; }
        .hero-stats { display: flex; gap: 2rem; margin-top: 3rem; }
        .stat-item .n { font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--gold2); }
        .stat-item .l { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.3); margin-top: 2px; }

        .ticker-wrap { margin-top: 3rem; overflow: hidden; width: 300px; position: relative; }
        .ticker-wrap::before, .ticker-wrap::after { content: ''; position: absolute; top: 0; bottom: 0; width: 40px; z-index: 2; pointer-events: none; }
        .ticker-wrap::before { left: 0; background: linear-gradient(90deg,var(--ink),transparent); }
        .ticker-wrap::after { right: 0; background: linear-gradient(-90deg,var(--ink),transparent); }
        .ticker { display: flex; gap: 2rem; animation: tickMove 20s linear infinite; white-space: nowrap; }
        @keyframes tickMove { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        .tick-item { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.25); padding: 6px 12px; border: 0.5px solid rgba(255,255,255,0.1); border-radius: 20px; }

        /* ── FORM PANEL ──────────────────────────────────────────────────── */
        .panel {
            position: relative; z-index: 2;
            background: var(--cream);
            display: flex; flex-direction: column; justify-content: center;
            padding: 3.5rem 3rem; overflow-y: auto;
            animation: slidePanel .9s cubic-bezier(.22,1,.36,1) both;
            box-shadow: -10px 0 30px rgba(0,0,0,0.5);
        }
        @keyframes slidePanel { from { transform: translateX(60px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        .panel-tex {
            position: absolute; inset: 0; opacity: .4; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='f'%3E%3CfeTurbulence baseFrequency='.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23f)' opacity='.04'/%3E%3C/svg%3E");
        }
        .panel-inner { position: relative; z-index: 1; }

        .tabs { display: flex; gap: 0; margin-bottom: 2.5rem; border-bottom: 1px solid rgba(0,0,0,0.1); }
        .tab-btn {
            flex: 1; padding: 10px; background: none; border: none; cursor: pointer;
            font-family: 'Syne', sans-serif; font-size: 12px; font-weight: 600;
            letter-spacing: 1.5px; text-transform: uppercase; color: #a09070;
            border-bottom: 2px solid transparent; margin-bottom: -1px; transition: all .25s;
        }
        .tab-btn.active { color: var(--sky); border-bottom-color: var(--gold); }
        .tab-btn:hover:not(.active) { color: var(--sky2); }

        .pane { display: none; animation: paneIn .4s ease both; }
        .pane.active { display: block; }
        @keyframes paneIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .form-heading { margin-bottom: 1.75rem; }
        .fh-over { font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase; color: var(--gold3); margin-bottom: .4rem; }
        .fh-title { font-family: 'Playfair Display', serif; font-size: 1.7rem; color: var(--ink); margin-bottom: .25rem; }
        .fh-sub { font-size: 13px; color: #8a7a5a; font-style: italic; }

        .fld { margin-bottom: 1.15rem; text-align: left; }
        .fld label { display: block; font-size: 10px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #8a7a5a; margin-bottom: .4rem; }
        .inp-w { position: relative; }
        .inp-ico { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #b0a080; pointer-events: none; transition: color .2s; }
        .inp-w input {
            width: 100%; padding: 13px 40px 13px 40px;
            border: 1px solid #ddd3b8; border-radius: 10px;
            font-family: 'Syne', sans-serif; font-size: 13.5px; color: var(--ink);
            background: #fffdf7; outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .inp-w input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212,168,67,0.15); }
        .inp-w:has(input:focus) .inp-ico { color: var(--gold3); }
        
        .pw-eye {
            position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #b0a080; cursor: pointer;
            font-size: 14px; padding: 4px; transition: color .2s;
        }
        .pw-eye:hover { color: var(--ink2); }

        .meta-row { display: flex; justify-content: space-between; align-items: center; margin: 1.25rem 0 1.75rem; }
        .rem-label { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #8a7a5a; cursor: pointer; }
        .rem-label input { accent-color: var(--gold3); width: 13px; height: 13px; }
        .fp-btn { font-size: 12px; color: var(--sky); text-decoration: none; font-weight: 600; transition: color .2s; background: none; border: none; cursor: pointer; padding: 0; }
        .fp-btn:hover { color: var(--gold3); }

        .btn-main {
            width: 100%; padding: 14px;
            background: var(--ink2); color: var(--gold2);
            border: 1px solid var(--gold3); border-radius: 10px;
            font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase; cursor: pointer;
            transition: background .3s, box-shadow .3s, transform .15s;
            position: relative; overflow: hidden;
        }
        .btn-main::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.05) 50%, rgba(255,255,255,0) 100%);
            transform: translateX(-100%); transition: transform .5s;
        }
        .btn-main:hover::after { transform: translateX(100%); }
        .btn-main:hover { background: var(--sky); box-shadow: 0 8px 28px rgba(10,14,26,0.3); transform: translateY(-2px); }
        .btn-main:active { transform: translateY(0); }

        .divider { display: flex; align-items: center; gap: .75rem; margin: 1.25rem 0; color: #c0b090; font-size: 11px; letter-spacing: 1px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e0d4b8; }

        .btn-secondary {
            width: 100%; padding: 12px; border-radius: 10px;
            border: 1px solid #d5c8a8; background: transparent;
            font-family: 'Syne', sans-serif; font-size: 12px; color: #6a5a3a;
            letter-spacing: 1px; cursor: pointer; transition: background .2s, border-color .2s; text-align: center;
        }
        .btn-secondary:hover { background: #f5edda; border-color: #c0a878; }

        .panel-footer { text-align: center; margin-top: 1.5rem; font-size: 12px; color: #a09070; }
        .panel-footer a, .panel-footer button { color: var(--sky); font-weight: 600; text-decoration: none; background: none; border: none; cursor: pointer; padding: 0; }
        .panel-footer a:hover, .panel-footer button:hover { color: var(--gold3); }

        /* ── STRENGTH BAR ────────────────────────────────────────────────── */
        .strength-track { height: 3px; background: #e8dfc8; border-radius: 2px; margin-top: 6px; overflow: hidden; }
        .strength-fill { height: 100%; width: 0%; border-radius: 2px; transition: width .3s, background .3s; }

        /* ── TOAST MESSAGES ──────────────────────────────────────────────── */
        .toast-zone { position: absolute; top: 20px; right: 20px; z-index: 200; display: flex; flex-direction: column; gap: 8px; }
        .toast {
            background: var(--ink2); color: var(--gold2);
            border: 1px solid var(--gold3); border-radius: 10px;
            padding: 10px 16px; font-size: 12px; font-weight: 600;
            letter-spacing: 1px; display: flex; align-items: center; gap: 8px;
            animation: toastIn .4s cubic-bezier(.34,1.56,.64,1) both, toastOut .3s ease 4s forwards;
            min-width: 220px; z-index: 9999;
        }
        @keyframes toastIn { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes toastOut { to { opacity: 0; transform: translateX(40px); } }

        @media(max-width: 820px) {
            .page { grid-template-columns: 1fr; }
            .hero { display: none; }
            .panel { padding: 2.5rem 1.75rem; min-height: 100vh; }
        }
    </style>
</head>
<body>

<div class="page">
    <div class="stars" id="starsEl"></div>
    <div class="aurora orb1"></div>
    <div class="aurora orb2"></div>
    <div class="aurora orb3"></div>

    <div class="hero">
        <div class="crest-ring">
            <div class="ring-outer"></div>
            <div class="ring-inner"></div>
            <div class="ring-dots">
                <div class="dot-orbit" style="--start:0deg;--od:9s"></div>
                <div class="dot-orbit" style="--start:120deg;--od:9s"></div>
                <div class="dot-orbit" style="--start:240deg;--od:9s"></div>
            </div>
            <div class="crest-core">
                <div class="crest-letters">BFI</div>
                <div class="crest-sub">Est. 2023</div>
            </div>
        </div>

        <div class="hero-tag">Brain Fart Institute</div>
        <h1 class="hero-h1">Where Odd Thoughts<br>Become <em>Brilliance</em></h1>
        <div class="hero-rule"><span></span></div>
        <p class="hero-motto">"Theory. Practice. Blip."</p>

        <div class="hero-stats">
            <div class="stat-item"><div class="n" id="sc1">0</div><div class="l">Students</div></div>
            <div class="stat-item"><div class="n" id="sc2">0</div><div class="l">Faculty</div></div>
            <div class="stat-item"><div class="n" id="sc3">0</div><div class="l">Courses</div></div>
        </div>

        <div class="ticker-wrap">
            <div class="ticker" id="tickEl">
                <span class="tick-item">Academics</span>
                <span class="tick-item">Research</span>
                <span class="tick-item">Innovation</span>
                <span class="tick-item">Excellence</span>
                <span class="tick-item">Discovery</span>
                <span class="tick-item">Curiosity</span>
                <span class="tick-item">Academics</span>
                <span class="tick-item">Research</span>
                <span class="tick-item">Innovation</span>
                <span class="tick-item">Excellence</span>
                <span class="tick-item">Discovery</span>
                <span class="tick-item">Curiosity</span>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-tex"></div>
        <div class="toast-zone" id="toastZone"></div>
        
        <div class="panel-inner">
            <div style="margin-bottom: 1rem;">
                @include('message')
            </div>

            <div class="tabs">
                <button type="button" class="tab-btn active" onclick="switchTab('login',this)">Sign In</button>
            </div>

            <div class="pane active" id="pane-login">
                <div class="form-heading">
                    <div class="fh-over">Student & Staff Portal</div>
                    <h2 class="fh-title">Welcome Back</h2>
                    <p class="fh-sub">Enter your credentials to access the portal</p>
                </div>

                <form method="POST" action="{{ url('login') }}">
                    @csrf
                    <div class="fld">
                        <label for="loginEmail">Email Address</label>
                        <div class="inp-w">
                            <span class="inp-ico">✉</span>
                            <input type="email" name="email" id="loginEmail" placeholder="you@bfi.edu" value="{{ old('email') }}" required autofocus />
                        </div>
                    </div>

                    <div class="fld">
                        <label for="loginPw">Password</label>
                        <div class="inp-w">
                            <span class="inp-ico">🔒</span>
                            <input type="password" name="password" id="loginPw" placeholder="Your password" required />
                            <button type="button" class="pw-eye" onclick="togglePw('loginPw',this)" tabindex="-1">👁</button>
                        </div>
                    </div>

                    <div class="meta-row">
                        <label class="rem-label">
                            <input type="checkbox" name="remember" value="1"> Remember me
                        </label>
                        <a href="{{ url('forget-password') }}" class="fp-btn">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-main">Enter Portal →</button>
                </form>

                {{-- <div class="divider">or continue with</div>
                <button type="button" class="btn-secondary" onclick="showToast('🎓','Google SSO coming soon!')">Continue with Google</button> --}}
                
                
            </div>


        </div>
    </div>
</div>

<script>
    // ── Generate Background Stars ──
    (function(){
        const stars = document.getElementById('starsEl');
        for(let i=0; i<80; i++){
            const s = document.createElement('div');
            const sz = Math.random() * 2.5 + .5;
            s.className = 'star';
            s.style.cssText = `width:${sz}px; height:${sz}px; left:${Math.random()*100}%; top:${Math.random()*100}%; --d:${2+Math.random()*4}s; --delay:${Math.random()*5}s; --op:${.3+Math.random()*.7}`;
            stars.appendChild(s);
        }
        animateCounter('sc1', 0, 1840, 1600);
        animateCounter('sc2', 0, 124, 1400);
        animateCounter('sc3', 0, 67, 1200);
    })();

    // ── Number Counter Animation ──
    function animateCounter(id, from, to, dur) {
        const el = document.getElementById(id);
        const start = performance.now();
        function step(now) {
            const p = Math.min((now - start) / dur, 1);
            const ease = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(from + (to - from) * ease);
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    // ── Tab Switcher ──
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.pane').forEach(p => p.classList.remove('active'));
        document.getElementById('pane-' + name).classList.add('active');
    }

    // ── Password Visibility Toggle ──
    function togglePw(id, btn) {
        const inp = document.getElementById(id);
        if(!inp) return;
        if(inp.type === 'password'){ inp.type = 'text'; btn.textContent = '🙈'; }
        else{ inp.type = 'password'; btn.textContent = '👁'; }
    }

    // ── Password Strength Meter ──
    function updateStrength(v) {
        let s = 0;
        if(v.length >= 8) s++; 
        if(/[A-Z]/.test(v)) s++; 
        if(/[0-9]/.test(v)) s++; 
        if(/[^A-Za-z0-9]/.test(v)) s++;
        
        const fills = ['0%', '25%', '50%', '75%', '100%'];
        const colors = ['#e8dfc8', '#c0392b', '#e67e22', '#2980b9', '#27ae60'];
        const f = document.getElementById('sFill');
        f.style.width = fills[s]; 
        f.style.background = colors[s];
    }

    // ── Toast Notification Generator ──
    function showToast(icon, msg) {
        const z = document.getElementById('toastZone');
        const t = document.createElement('div');
        t.className = 'toast';
        t.innerHTML = `<span style="font-size:16px">${icon}</span><span>${msg}</span>`;
        z.appendChild(t);
        setTimeout(() => { if(t.parentNode) t.parentNode.removeChild(t) }, 4500);
    }

    // ── Catch Laravel Validation Errors and Session Flashes ──
    document.addEventListener("DOMContentLoaded", function() {
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                showToast('⚠', '{{ $error }}');
            @endforeach
        @endif

        @if (session('error'))
            showToast('⚠', '{{ session('error') }}');
        @endif

        @if (session('success'))
            showToast('✅', '{{ session('success') }}');
        @endif

        @if (session('status'))
            showToast('ℹ', '{{ session('status') }}');
        @endif
    });
</script>
</body>
</html>