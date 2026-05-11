<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <div class="login-split container">
        <div class="login-left card">
            <img src="{{ asset('images/mushroom.png') }}" alt="mushroom" class="login-illustration">
            <h3 class="login-title">Pelajari Tentang Penyakit Jamur Tiram</h3>
            <p class="login-sub">Diagnosa dengan cepat menggunakan sistem pendukung keputusan berbasis SAW. Bersih, cepat, dan andal.</p>
        </div>

        <div class="login-right card">
            <h2 style="margin:0 0 12px 0">Login ke SPK Penyakit Jamur Tiram</h2>
            <p style="color:var(--muted);margin-bottom:18px">Masukkan Data Diri Anda untuk melanjutkan</p>

            @if(session('status'))
                <div class="card" style="background:#ecfeff;border-left:4px solid var(--accent);margin-bottom:12px">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-row">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}">
                    @error('email')<div style="color:#b91c1c;margin-top:6px">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password">
                    @error('password')<div style="color:#b91c1c;margin-top:6px">{{ $message }}</div>@enderror
                </div>

                <div class="form-row" style="display:flex;align-items:center;justify-content:space-between">
                    <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="remember"> Ingat Saya</label>
                    <div>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="color:var(--accent)">Lupa?</a>
                        @endif
                    </div>
                </div>

                <div style="display:flex;gap:10px;align-items:center;margin-top:6px">
                    <button class="btn btn-primary" type="submit">Log in</button>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn" style="border:1px solid #e6edf3">Buat akun</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>
