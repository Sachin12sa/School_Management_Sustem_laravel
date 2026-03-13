<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Brain Fart Institute — Reset Password</title>
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

        .wrap{min-height:100vh;display:grid;grid-template-columns:1fr 460px}

        /* ─── HERO ───────────────────────────────────────────────────── */
        .hero{
            background:var(--navy-deep);position:relative;overflow:hidden;
            display:flex;flex-direction:column;align-items:center;
            justify-content:center;padding:4rem 3rem;text-align:center;
        }
        .hero::before,.hero::after{content:'';position:absolute;border:1px solid rgba(201,168,76,.15)}
        .hero::before{inset:24px}.hero::after{inset:32px}
        .hero-tex{position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,transparent,transparent 40px,rgba(201,168,76,.025) 40px,rgba(201,168,76,.025) 41px)}
        .corner{position:absolute;width:60px;height:60px;border-color:rgba(201,168,76,.28);border-style:solid}
        .corner.tl{top:20px;left:20px;border-width:2px 0 0 2px}
        .corner.tr{top:20px;right:20px;border-width:2px 2px 0 0}
        .corner.bl{bottom:20px;left:20px;border-width:0 0 2px 2px}
        .corner.br{bottom:20px;right:20px;border-width:0 2px 2px 0}

        .hero-inner{position:relative;z-index:1;animation:fadeUp .8s ease both}

        /* Animated shield/key */
        .icon-anim{
            width:110px;height:110px;margin:0 auto 2rem;
            border-radius:50%;border:2px solid rgba(201,168,76,.25);
            background:rgba(201,168,76,.06);
            display:flex;align-items:center;justify-content:center;
        }
        .icon-anim i{font-size:2.2rem;color:var(--gold);animation:keyTurn 3s ease-in-out infinite}
        @keyframes keyTurn{0%,100%{transform:rotate(-10deg)}50%{transform:rotate(10deg)}}

        .hero-subtitle{font-size:.68rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-bottom:.6rem}
        .hero-title{font-family:'Playfair Display',serif;font-size:clamp(1.5rem,2.2vw,2rem);color:#fff;line-height:1.3;margin-bottom:.9rem}
        .hero-title em{color:var(--gold-lt);font-style:italic}
        .hero-rule{width:60px;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent);margin:.75rem auto}
        .hero-desc{font-family:'Crimson Text',serif;font-style:italic;color:rgba(255,255,255,.5);font-size:1rem;max-width:280px;margin:0 auto 2.5rem}

        .tip-list{display:flex;flex-direction:column;gap:.6rem;text-align:left;max-width:260px;margin:0 auto}
        .tip{display:flex;align-items:center;gap:9px}
        .tip i{color:var(--gold);font-size:.8rem;flex-shrink:0}
        .tip span{color:rgba(255,255,255,.55);font-size:.82rem}

        /* ─── FORM PANEL ──────────────────────────────────────────────── */
        .panel{
            background:var(--cream);border-left:1px solid rgba(201,168,76,.2);
            display:flex;flex-direction:column;justify-content:center;
            padding:3.5rem 3rem;overflow-y:auto;position:relative;
        }
        .panel::before{content:'';position:absolute;inset:0;pointer-events:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");opacity:.4}
        .p-inner{position:relative;z-index:1}

        .back-link{display:inline-flex;align-items:center;gap:6px;color:var(--muted);font-size:.8rem;text-decoration:none;margin-bottom:1.75rem;transition:color .2s}
        .back-link:hover{color:var(--navy)}

        .form-kicker{font-size:.68rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold-dk);margin-bottom:.5rem;animation:fadeUp .6s .05s ease both}
        .form-title{font-family:'Playfair Display',serif;font-size:1.75rem;color:var(--navy);margin-bottom:.3rem;animation:fadeUp .6s .1s ease both}
        .form-sub{font-family:'Crimson Text',serif;font-style:italic;color:var(--muted);font-size:.98rem;margin-bottom:1.75rem;line-height:1.55;animation:fadeUp .6s .15s ease both}
        .divider-line{display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;animation:fadeUp .6s .18s ease both}
        .divider-line::before,.divider-line::after{content:'';flex:1;height:1px;background:var(--border)}
        .divider-line span{font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
        .flash{animation:fadeUp .5s .2s ease both}

        .fld{margin-bottom:1.2rem;animation:fadeUp .6s ease both}
        .fld:nth-child(1){animation-delay:.22s}.fld:nth-child(2){animation-delay:.3s}
        .fld label{display:block;font-size:.72rem;font-weight:500;letter-spacing:.07em;text-transform:uppercase;color:var(--muted);margin-bottom:.45rem}
        .inp-w{position:relative}
        .inp-w .ico{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.9rem;pointer-events:none;transition:color .2s}
        .inp-w input{
            width:100%;padding:12px 42px 12px 40px;
            border:1px solid var(--border);border-radius:var(--radius);
            font-family:'DM Sans',sans-serif;font-size:.92rem;color:var(--text);
            background:#fffdf7;outline:none;
            transition:border-color .2s,box-shadow .2s,background .2s;
        }
        .inp-w input:focus{border-color:var(--gold);background:#fff;box-shadow:0 0 0 3px rgba(201,168,76,.15)}
        .inp-w:has(input:focus) .ico{color:var(--gold-dk)}
        .pw-btn{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;padding:4px;font-size:.88rem;transition:color .2s}
        .pw-btn:hover{color:var(--navy)}

        /* Strength */
        .strength-row{margin-top:.45rem;animation:fadeUp .6s .32s ease both}
        .s-track{height:4px;border-radius:2px;background:var(--border);overflow:hidden;margin-bottom:.25rem}
        .s-fill{height:100%;border-radius:2px;width:0%;transition:width .35s,background .35s}
        .s-lbl{font-size:.7rem;color:var(--muted)}
        .match-msg{margin-top:.4rem;font-size:.72rem;min-height:1.1em}

        .btn-reset{
            width:100%;padding:13px;background:var(--navy);color:var(--gold-lt);
            border:1px solid var(--gold-dk);border-radius:var(--radius);
            font-family:'DM Sans',sans-serif;font-size:.95rem;font-weight:500;
            cursor:pointer;letter-spacing:.04em;
            transition:background .25s,box-shadow .25s,transform .15s;
            animation:fadeUp .6s .38s ease both;margin-top:.5rem;
        }
        .btn-reset:hover{background:var(--navy-mid);box-shadow:0 6px 20px rgba(14,31,61,.22);transform:translateY(-1px)}
        .btn-reset:active{transform:translateY(0)}
        .btn-reset span{display:flex;align-items:center;justify-content:center;gap:8px}

        .gold-rule{height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent);margin:1.4rem 0;animation:fadeUp .6s .42s ease both}
        .panel-footer{text-align:center;font-size:.82rem;color:var(--muted);animation:fadeUp .6s .46s ease both}
        .panel-footer a{color:var(--navy);font-weight:500;text-decoration:none}
        .panel-footer a:hover{color:var(--gold-dk)}
        .est-badge{display:flex;align-items:center;justify-content:center;gap:.5rem;margin-top:1.25rem;font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(107,94,62,.45);animation:fadeUp .6s .5s ease both}
        .est-badge::before,.est-badge::after{content:'✦';font-size:.5rem;color:var(--gold);opacity:.45}

        @keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
        @media(max-width:820px){.wrap{grid-template-columns:1fr}.hero{display:none}.panel{padding:2.5rem 1.75rem;min-height:100vh}}
    </style>
</head>
<body>
<div class="wrap">

    <div class="hero">
        <div class="hero-tex"></div>
        <div class="corner tl"></div><div class="corner tr"></div>
        <div class="corner bl"></div><div class="corner br"></div>
        <div class="hero-inner">
            <div class="icon-anim"><i class="bi bi-key-fill"></i></div>
            <p class="hero-subtitle">Security Update</p>
            <h1 class="hero-title">Forge a <em>Stronger</em><br>New Password</h1>
            <div class="hero-rule"></div>
            <p class="hero-desc">"Security is the foundation upon which all learning is built."</p>
            <div class="tip-list">
                <div class="tip"><i class="bi bi-check-circle-fill"></i><span>At least 8 characters</span></div>
                <div class="tip"><i class="bi bi-check-circle-fill"></i><span>Uppercase &amp; lowercase letters</span></div>
                <div class="tip"><i class="bi bi-check-circle-fill"></i><span>At least one number</span></div>
                <div class="tip"><i class="bi bi-check-circle-fill"></i><span>A symbol or special character</span></div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="p-inner">
            <a href="{{ url('') }}" class="back-link"><i class="bi bi-arrow-left"></i> Back to Sign In</a>
            <p class="form-kicker">New Credentials</p>
            <h2 class="form-title">Reset Your Password</h2>
            <p class="form-sub">Choose a strong new password to secure your account.</p>

            <div class="divider-line"><span>Secure Reset</span></div>
            <div class="flash">@include('message')</div>

            <form action="" method="POST">
                @csrf

                <div class="fld">
                    <label for="newPw">New Password</label>
                    <div class="inp-w">
                        <i class="bi bi-lock-fill ico"></i>
                        <input type="password" id="newPw" name="password"
                               placeholder="Enter new password" required>
                        <button type="button" class="pw-btn" onclick="togglePw('newPw',this)" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="strength-row">
                        <div class="s-track"><div class="s-fill" id="sFill"></div></div>
                        <div class="s-lbl" id="sLbl"></div>
                    </div>
                </div>

                <div class="fld">
                    <label for="confPw">Confirm Password</label>
                    <div class="inp-w">
                        <i class="bi bi-lock-fill ico"></i>
                        <input type="password" id="confPw" name="cpassword"
                               placeholder="Repeat new password" required>
                        <button type="button" class="pw-btn" onclick="togglePw('confPw',this)" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="match-msg" id="matchMsg"></div>
                </div>

                <button type="submit" class="btn-reset">
                    <span><i class="bi bi-shield-check-fill"></i> Confirm New Password</span>
                </button>
            </form>

            <div class="gold-rule"></div>
            <div class="panel-footer">Remembered it? <a href="{{ url('') }}">Sign in instead</a></div>
            <div class="est-badge">Brain Fart Institute &nbsp;·&nbsp; Est. 2023</div>
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
var np=document.getElementById('newPw'),cp=document.getElementById('confPw');
var fill=document.getElementById('sFill'),lbl=document.getElementById('sLbl'),msg=document.getElementById('matchMsg');
var lvl=[
    {w:'0%',bg:'',t:''},
    {w:'25%',bg:'#c0392b',t:'Weak'},
    {w:'50%',bg:'#e67e22',t:'Fair'},
    {w:'75%',bg:'#2980b9',t:'Good'},
    {w:'100%',bg:'#27ae60',t:'Strong'}
];
np.addEventListener('input',function(){
    var v=this.value,s=0;
    if(v.length>=8)s++;if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
    fill.style.width=lvl[s].w;fill.style.background=lvl[s].bg;
    lbl.textContent=lvl[s].t?'Strength: '+lvl[s].t:'';lbl.style.color=lvl[s].bg;
    checkMatch();
});
function checkMatch(){
    if(!cp.value){msg.textContent='';return;}
    if(np.value===cp.value){msg.textContent='✓ Passwords match';msg.style.color='#27ae60';}
    else{msg.textContent='✗ Does not match';msg.style.color='#c0392b';}
}
cp.addEventListener('input',checkMatch);
</script>
</body>
</html>