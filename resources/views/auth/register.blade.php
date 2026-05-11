<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <div class="login-split container">
        <div class="login-left card">
            <img src="{{ asset('images/mushroom.png') }}" alt="mushroom" class="login-illustration">
            <h3 class="login-title">Join SPK Penyakit Jamur Tiram</h3>
            <p class="login-sub">Buat akun untuk menyimpan dan mengelola diagnosis. Cepat, andal, didukung oleh SAW.</p>
        </div>

        <div class="login-right card">
            <h2 style="margin:0 0 12px 0">Register</h2>
            <p style="color:var(--muted);margin-bottom:18px">Isi formulir untuk membuat akun Anda</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-row">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}">
                    @error('name')<div style="color:#b91c1c;margin-top:6px">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}">
                    @error('email')<div style="color:#b91c1c;margin-top:6px">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password">
                    @error('password')<div style="color:#b91c1c;margin-top:6px">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
                </div>

                <div style="display:flex;gap:10px;align-items:center;margin-top:6px">
                    <button class="btn btn-primary" type="submit">Register</button>
                    <a href="{{ route('login') }}" class="btn" style="border:1px solid #e6edf3">Sudah memiliki akun?</a>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>
