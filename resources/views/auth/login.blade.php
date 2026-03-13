<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Brain Fart Institute — Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Crimson+Text:ital@0;1&family=DM+Sans:wght@400;500&display=swap" />
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --navy:#0e1f3d;--navy-deep:#081428;--navy-mid:#152d56;
            --gold:#c9a84c;--gold-lt:#e8cc8a;--gold-dk:#9a7a2a;
            --cream:#f8f4ec;--parchment:#f2ead8;
            --text:#1a1408;--muted:#6b5e3e;
            --border:#d4c090;--radius:10px;
        }
        html,body{height:100%;font-family:'DM Sans',sans-serif;background:var(--parchment);color:var(--text)}

        /* ─── LAYOUT ─────────────────────────────────────────────────── */
        .wrap{min-height:100vh;display:grid;grid-template-columns:1fr 460px}

        /* ─── HERO ───────────────────────────────────────────────────── */
        .hero{
            background:var(--navy);
            position:relative;overflow:hidden;
            display:flex;flex-direction:column;align-items:center;
            justify-content:center;padding:4rem 3rem;text-align:center;
        }
        /* Subtle corner ornament lines */
        .hero::before,.hero::after{content:'';position:absolute;border:1px solid rgba(201,168,76,.18)}
        .hero::before{inset:24px;pointer-events:none}
        .hero::after{inset:32px;pointer-events:none}

        /* Diagonal stripe texture */
        .hero-tex{
            position:absolute;inset:0;
            background-image:repeating-linear-gradient(
                45deg,
                transparent,transparent 40px,
                rgba(201,168,76,.03) 40px,rgba(201,168,76,.03) 41px
            );
        }
        /* Corner flourishes via CSS */
        .corner{position:absolute;width:60px;height:60px;border-color:rgba(201,168,76,.3);border-style:solid}
        .corner.tl{top:20px;left:20px;border-width:2px 0 0 2px}
        .corner.tr{top:20px;right:20px;border-width:2px 2px 0 0}
        .corner.bl{bottom:20px;left:20px;border-width:0 0 2px 2px}
        .corner.br{bottom:20px;right:20px;border-width:0 2px 2px 0}

        .hero-inner{position:relative;z-index:1;animation:fadeUp .8s ease both}

        /* Crest container */
        .crest-wrap{
            width:160px;height:160px;margin:0 auto 2rem;
            border-radius:50%;
            border:2px solid rgba(201,168,76,.3);
            background:rgba(201,168,76,.06);
            display:flex;align-items:center;justify-content:center;
            animation:floatCrest 4s ease-in-out infinite;
            position:relative;
        }
        .crest-wrap::before{
            content:'';position:absolute;inset:-8px;border-radius:50%;
            border:1px solid rgba(201,168,76,.15);
        }
        .crest-img{width:120px;height:120px;object-fit:contain;filter:drop-shadow(0 4px 16px rgba(0,0,0,.4))}
        @keyframes floatCrest{0%,100%{transform:translateY(0)}50%{transform:translateY(-7px)}}

        .hero-subtitle{
            font-size:.68rem;letter-spacing:.2em;text-transform:uppercase;
            color:var(--gold);margin-bottom:.75rem;font-family:'DM Sans',sans-serif;
        }
        .hero-title{
            font-family:'Playfair Display',serif;
            font-size:clamp(1.5rem,2.4vw,2.1rem);
            color:#fff;line-height:1.25;margin-bottom:1rem;
        }
        .hero-title em{color:var(--gold-lt);font-style:italic}
        .hero-rule{
            width:80px;height:1px;
            background:linear-gradient(90deg,transparent,var(--gold),transparent);
            margin:.9rem auto;
        }
        .hero-motto{
            font-family:'Crimson Text',serif;font-style:italic;
            font-size:1.05rem;color:rgba(255,255,255,.5);letter-spacing:.04em;
        }
        .hero-tagline{
            margin-top:2rem;display:flex;gap:1.5rem;justify-content:center;
        }
        .tag{
            font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;
            color:rgba(255,255,255,.35);font-family:'DM Sans',sans-serif;
        }
        .tag-sep{color:rgba(201,168,76,.4);font-size:.8rem}

        /* ─── FORM PANEL ──────────────────────────────────────────────── */
        .panel{
            background:var(--cream);
            border-left:1px solid rgba(201,168,76,.2);
            display:flex;flex-direction:column;justify-content:center;
            padding:3.5rem 3rem;overflow-y:auto;
            position:relative;
        }
        /* Parchment grain */
        .panel::before{
            content:'';position:absolute;inset:0;pointer-events:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            opacity:.4;
        }

        .p-inner{position:relative;z-index:1}

        .form-kicker{
            font-size:.68rem;letter-spacing:.18em;text-transform:uppercase;
            color:var(--gold-dk);margin-bottom:.6rem;font-family:'DM Sans',sans-serif;
            animation:fadeUp .6s .05s ease both;
        }
        .form-title{
            font-family:'Playfair Display',serif;
            font-size:1.85rem;color:var(--navy);margin-bottom:.35rem;
            animation:fadeUp .6s .1s ease both;
        }
        .form-sub{
            font-family:'Crimson Text',serif;font-style:italic;
            color:var(--muted);font-size:1rem;margin-bottom:2rem;
            animation:fadeUp .6s .15s ease both;
        }

        .divider-line{
            display:flex;align-items:center;gap:.75rem;margin-bottom:1.75rem;
            animation:fadeUp .6s .18s ease both;
        }
        .divider-line::before,.divider-line::after{content:'';flex:1;height:1px;background:var(--border)}
        .divider-line span{font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}

        /* Flash */
        .flash{animation:fadeUp .5s .2s ease both}

        /* Fields */
        .fld{margin-bottom:1.2rem;animation:fadeUp .6s ease both}
        .fld:nth-child(1){animation-delay:.22s}
        .fld:nth-child(2){animation-delay:.3s}
        .fld label{
            display:block;font-size:.72rem;font-weight:500;
            letter-spacing:.07em;text-transform:uppercase;
            color:var(--muted);margin-bottom:.45rem;
        }
        .inp-w{position:relative}
        .inp-w .ico{
            position:absolute;left:13px;top:50%;transform:translateY(-50%);
            color:var(--muted);font-size:.9rem;pointer-events:none;transition:color .2s;
        }
        .inp-w input{
            width:100%;padding:12px 42px 12px 40px;
            border:1px solid var(--border);border-radius:var(--radius);
            font-family:'DM Sans',sans-serif;font-size:.92rem;
            color:var(--text);background:#fffdf7;outline:none;
            transition:border-color .2s,box-shadow .2s,background .2s;
        }
        .inp-w input:focus{
            border-color:var(--gold);background:#fff;
            box-shadow:0 0 0 3px rgba(201,168,76,.15);
        }
        .inp-w:has(input:focus) .ico{color:var(--gold-dk)}
        .pw-btn{
            position:absolute;right:12px;top:50%;transform:translateY(-50%);
            background:none;border:none;color:var(--muted);cursor:pointer;
            padding:4px;font-size:.88rem;transition:color .2s;
        }
        .pw-btn:hover{color:var(--navy)}

        .row-meta{
            display:flex;justify-content:space-between;align-items:center;
            margin-bottom:1.6rem;animation:fadeUp .6s .36s ease both;
        }
        .remember{display:flex;align-items:center;gap:7px;cursor:pointer;font-size:.83rem;color:var(--muted)}
        .remember input{accent-color:var(--gold-dk);width:14px;height:14px}
        .fp-link{font-size:.83rem;color:var(--navy);text-decoration:none;font-weight:500;transition:color .2s}
        .fp-link:hover{color:var(--gold-dk)}

        .btn-signin{
            width:100%;padding:13px;
            background:var(--navy);color:var(--gold-lt);
            border:1px solid var(--gold-dk);border-radius:var(--radius);
            font-family:'DM Sans',sans-serif;font-size:.95rem;font-weight:500;
            cursor:pointer;letter-spacing:.04em;
            transition:background .25s,box-shadow .25s,transform .15s;
            animation:fadeUp .6s .42s ease both;
        }
        .btn-signin:hover{background:var(--navy-mid);box-shadow:0 6px 20px rgba(14,31,61,.25);transform:translateY(-1px)}
        .btn-signin:active{transform:translateY(0)}
        .btn-signin span{display:flex;align-items:center;justify-content:center;gap:8px}

        /* Gold rule separator */
        .gold-rule{
            height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent);
            margin:1.5rem 0;animation:fadeUp .6s .46s ease both;
        }
        .panel-footer{
            text-align:center;font-size:.82rem;color:var(--muted);
            animation:fadeUp .6s .5s ease both;
        }
        .panel-footer a{color:var(--navy);font-weight:500;text-decoration:none}
        .panel-footer a:hover{color:var(--gold-dk)}

        .est-badge{
            display:flex;align-items:center;justify-content:center;gap:.5rem;
            margin-top:1.25rem;font-size:.68rem;letter-spacing:.1em;
            text-transform:uppercase;color:rgba(107,94,62,.5);
            animation:fadeUp .6s .54s ease both;
        }
        .est-badge::before,.est-badge::after{content:'✦';font-size:.5rem;color:var(--gold);opacity:.5}

        @keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}

        @media(max-width:820px){
            .wrap{grid-template-columns:1fr}
            .hero{display:none}
            .panel{padding:2.5rem 1.75rem;min-height:100vh}
        }
    </style>
</head>
<body>
<div class="wrap">

    {{-- HERO --}}
    <div class="hero">
        <div class="hero-tex"></div>
        <div class="corner tl"></div><div class="corner tr"></div>
        <div class="corner bl"></div><div class="corner br"></div>
        <div class="hero-inner">
            <div class="crest-wrap">
                <img src="{{ asset('images/school_logo.png') }}" alt="BFI Crest" class="crest-img"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div style="display:none;flex-direction:column;align-items:center;gap:4px">
                    <span style="font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--gold);line-height:1">BFI</span>
                    <span style="font-size:.6rem;letter-spacing:.15em;text-transform:uppercase;color:rgba(201,168,76,.6)">Est. 2023</span>
                </div>
            </div>
            <p class="hero-subtitle">Est. 2023 · Theory. Practice. Blip.</p>
            <h1 class="hero-title">Brain Fart<br><em>Institute</em></h1>
            <div class="hero-rule"></div>
            <p class="hero-motto">"Where every thought counts — even the odd ones."</p>
            <div class="hero-tagline">
                <span class="tag">Academics</span>
                <span class="tag-sep">◆</span>
                <span class="tag">Research</span>
                <span class="tag-sep">◆</span>
                <span class="tag">Discovery</span>
            </div>
        </div>
    </div>

    {{-- FORM PANEL --}}
    <div class="panel">
        <div class="p-inner">
            <p class="form-kicker">Student & Staff Portal</p>
            <h2 class="form-title">Welcome Back</h2>
            <p class="form-sub">Sign in to continue to your session</p>

            <div class="divider-line"><span>Secure Access</span></div>

            <div class="flash">@include('message')</div>

            <form action="{{ url('login') }}" method="POST">
                @csrf

                <div class="fld">
                    <label for="loginEmail">Email Address</label>
                    <div class="inp-w">
                        <i class="bi bi-envelope ico"></i>
                        <input type="email" id="loginEmail" name="email"
                               value="{{ old('email') }}"
                               placeholder="your@bfi.edu" required>
                    </div>
                </div>

                <div class="fld">
                    <label for="loginPass">Password</label>
                    <div class="inp-w">
                        <i class="bi bi-lock-fill ico"></i>
                        <input type="password" id="loginPass" name="password"
                               placeholder="Enter your password" required>
                        <button type="button" class="pw-btn" onclick="togglePw('loginPass',this)" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="row-meta">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1"> Remember me
                    </label>
                    <a href="{{ url('forget-password') }}" class="fp-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-signin">
                    <span><i class="bi bi-box-arrow-in-right"></i> Sign In to Portal</span>
                </button>
            </form>

            <div class="gold-rule"></div>
            <div class="panel-footer">
                Trouble accessing? <a href="{{ url('forget-password') }}">Reset your credentials</a>
            </div>
            <div class="est-badge">Brain Fart Institute &nbsp;·&nbsp; All Rights Reserved</div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<script>
function togglePw(id,btn){
    var i=document.getElementById(id),ic=btn.querySelector('i');
    if(i.type==='password'){i.type='text';ic.className='bi bi-eye-slash';}
    else{i.type='password';ic.className='bi bi-eye';}
}
</script>
</body>
</html>