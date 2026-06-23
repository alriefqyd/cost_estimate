<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — Cost Estimate</title>
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
        }

        /* ── Brand panel ─────────────────────────────────────────── */
        .lp-brand {
            flex: 0 0 42%;
            background: linear-gradient(155deg, #1a4d44 0%, #24695c 55%, #2d8572 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }

        .lp-brand-ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .lp-brand-ring:nth-child(1) {
            width: 500px; height: 500px; top: -160px; right: -160px;
            animation: lp-spin-cw 28s linear infinite;
        }
        .lp-brand-ring:nth-child(2) {
            width: 340px; height: 340px; bottom: -100px; left: -100px;
            animation: lp-spin-ccw 22s linear infinite;
        }
        .lp-brand-ring:nth-child(3) {
            width: 200px; height: 200px; bottom: 60px; right: -60px;
            border-color: rgba(243,193,7,0.18);
            animation: lp-spin-cw 16s linear infinite;
        }
        @keyframes lp-spin-cw  { to { transform: rotate(360deg);  } }
        @keyframes lp-spin-ccw { to { transform: rotate(-360deg); } }

        .lp-brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
            animation: lp-brand-in 0.7s cubic-bezier(.22,.68,0,1.1) 0.15s both;
        }

        @keyframes lp-brand-in {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .lp-brand-logo {
            width: 110px;
            margin-bottom: 36px;
            filter: brightness(0) invert(1);
            animation: lp-logo-pulse 4s ease-in-out 1.4s infinite;
        }

        @keyframes lp-logo-pulse {
            0%, 100% { filter: brightness(0) invert(1); transform: scale(1); }
            50%       { filter: brightness(0) invert(1) drop-shadow(0 0 12px rgba(255,255,255,0.35)); transform: scale(1.04); }
        }

        .lp-brand-title {
            color: #fff;
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 14px;
            line-height: 1.25;
        }

        .lp-brand-divider {
            width: 40px;
            height: 3px;
            background: #f3c107;
            border-radius: 2px;
            margin: 0 auto 40px;
            animation: lp-divider-grow 0.5s ease-out 0.5s both;
        }

        @keyframes lp-divider-grow {
            from { width: 0; opacity: 0; }
            to   { width: 40px; opacity: 1; }
        }

        .lp-brand-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .lp-brand-pill {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.8);
            font-size: 0.76rem;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 20px;
            letter-spacing: 0.3px;
            opacity: 0;
            animation: lp-pill-in 0.4s ease-out forwards;
        }
        .lp-brand-pill:nth-child(1) { animation-delay: 0.6s; }
        .lp-brand-pill:nth-child(2) { animation-delay: 0.72s; }
        .lp-brand-pill:nth-child(3) { animation-delay: 0.84s; }
        .lp-brand-pill:nth-child(4) { animation-delay: 0.96s; }
        .lp-brand-pill:nth-child(5) { animation-delay: 1.08s; }
        .lp-brand-pill:nth-child(6) { animation-delay: 1.20s; }
        @keyframes lp-pill-in {
            from { opacity: 0; transform: translateY(10px) scale(0.92); }
            to   { opacity: 1; transform: translateY(0)   scale(1); }
        }

        /* ── Form panel ──────────────────────────────────────────── */
        .lp-form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
            background: #f5f7fa;
        }

        .lp-form-box {
            width: 100%;
            max-width: 400px;
            animation: lp-rise 0.45s cubic-bezier(.22,.68,0,1.2) both;
        }

        @keyframes lp-rise {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .lp-heading {
            font-size: 1.7rem;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.4px;
            margin-bottom: 6px;
        }

        .lp-subheading {
            font-size: 0.88rem;
            color: #6b7280;
            margin-bottom: 36px;
        }

        /* Alerts */
        .lp-alert-error {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            font-size: 0.84rem;
            color: #dc2626;
            line-height: 1.55;
        }

        .lp-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            font-size: 0.84rem;
            color: #16a34a;
        }

        /* Fields */
        .lp-field { margin-bottom: 18px; }

        .lp-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 7px;
            transition: color 0.2s;
        }

        .lp-field:focus-within .lp-label { color: #24695c; }

        .lp-input-wrap { position: relative; }

        .lp-input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.85rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .lp-input {
            width: 100%;
            height: 48px;
            padding: 0 44px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            font-size: 0.93rem;
            color: #111827;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .lp-input::placeholder { color: #c0c8d4; }

        .lp-input:focus {
            border-color: #24695c;
            box-shadow: 0 0 0 3.5px rgba(36,105,92,0.12);
        }

        .lp-input-wrap:focus-within .lp-input-icon { color: #24695c; }

        .lp-eye {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            font-size: 0.85rem;
            transition: color 0.2s;
            padding: 4px;
        }
        .lp-eye:hover { color: #24695c; }

        /* Forgot row */
        .lp-meta {
            display: flex;
            justify-content: flex-end;
            margin: -4px 0 28px;
        }

        .lp-forgot {
            font-size: 0.82rem;
            font-weight: 500;
            color: #24695c;
            text-decoration: none;
        }
        .lp-forgot:hover { text-decoration: underline; }

        /* Submit */
        .lp-btn {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #24695c 0%, #1a4d44 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.3px;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(36,105,92,0.3);
        }

        .lp-btn:hover  { opacity: 0.93; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(36,105,92,0.35); }
        .lp-btn:active { transform: translateY(0); box-shadow: 0 2px 8px rgba(36,105,92,0.25); }
        .lp-btn.loading { pointer-events: none; opacity: 0.8; }
        .lp-btn .lp-spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: lp-spin-cw 0.7s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }
        .lp-btn.loading .lp-spinner { display: inline-block; }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 820px) {
            .lp-brand { display: none; }
            .lp-form-panel { background: #fff; padding: 40px 28px; }
        }
    </style>
</head>
<body>

    {{-- Brand panel --}}
    <div class="lp-brand" aria-hidden="true">
        <div class="lp-brand-ring"></div>
        <div class="lp-brand-ring"></div>
        <div class="lp-brand-ring"></div>
        <div class="lp-brand-content">
            <img class="lp-brand-logo" src="{{ asset('assets/images/Vale_logo.svg') }}" alt="Vale">
            <div class="lp-brand-title">Cost Estimate<br>Management</div>
<div class="lp-brand-divider"></div>
            <div class="lp-brand-pills">
                <span class="lp-brand-pill">Civil</span>
                <span class="lp-brand-pill">Mechanical</span>
                <span class="lp-brand-pill">Electrical</span>
                <span class="lp-brand-pill">Instrument</span>
                <span class="lp-brand-pill">Architect</span>
                <span class="lp-brand-pill">IT</span>
            </div>
        </div>
    </div>

    {{-- Form panel --}}
    <div class="lp-form-panel">
        <div class="lp-form-box">

            <h1 class="lp-heading">Welcome back</h1>
            <p class="lp-subheading">Sign in to your account to continue</p>

            @if ($errors->any())
                <div class="lp-alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('status'))
                <div class="lp-alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="lp-field">
                    <label class="lp-label" for="user_name">Username</label>
                    <div class="lp-input-wrap">
                        <i class="lp-input-icon fa fa-user"></i>
                        <input id="user_name" class="lp-input" name="user_name"
                               type="text" value="{{ old('user_name') }}"
                               placeholder="Enter your username"
                               required autofocus>
                    </div>
                </div>

                <div class="lp-field">
                    <label class="lp-label" for="password">Password</label>
                    <div class="lp-input-wrap">
                        <i class="lp-input-icon fa fa-lock"></i>
                        <input id="password" class="lp-input js-user-password" name="password"
                               type="password" autocomplete="off"
                               placeholder="Enter your password">
                        <i class="lp-eye fa fa-eye js-show-hide-password"></i>
                    </div>
                </div>

                <div class="lp-meta">
                    <a class="lp-forgot" href="/forgot-password">Forgot password?</a>
                </div>

                <button class="lp-btn" type="submit">
                    <span class="lp-spinner"></span>Sign in
                </button>
            </form>

        </div>
    </div>

<script src="{{ asset('/assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('/js/login.js') }}"></script>
</body>
</html>
