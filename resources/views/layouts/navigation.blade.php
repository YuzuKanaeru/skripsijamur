<div>
    <!-- Sidebar -->
    <aside class="sidebar">
        @unless(request()->is('login*') || request()->is('register*'))
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-inner">
                <div class="brand-box"><img src="{{ asset('images/mushroomlogotrans.png') }}" alt="logo" /></div>
            </a>
        </div>
        @endunless

        <nav>
            <div class="menu-section">
                <div class="section-title"></div>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="menu-icon">📊</span><span>Dashboard</span></a>
                <a href="{{ route('diagnose.create') }}" class="{{ request()->routeIs('diagnose.create') ? 'active' : '' }}"><span class="menu-icon">🩺</span><span>Diagnosa</span></a>
                <a href="{{ route('diagnose.index') }}" class="{{ request()->routeIs('diagnose.index') ? 'active' : '' }}"><span class="menu-icon">🕘</span><span>Riwayat Penilaian</span></a>
                <a href="{{ route('penyakit.index') }}" class="{{ request()->routeIs('penyakit.index') ? 'active' : '' }}"><span class="menu-icon">📋</span><span>Daftar Penyakit</span></a>
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.penyakit.index') }}" class="{{ request()->is('admin/penyakit*') ? 'active' : '' }}"><span class="menu-icon">🦠</span><span>Penyakit</span></a>
                    <a href="{{ route('admin.kriteria.index') }}" class="{{ request()->is('admin/kriteria*') ? 'active' : '' }}"><span class="menu-icon">⚖️</span><span>Kriteria</span></a>
                @endif
            </div>
        </nav>
    </aside>

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-brand"></div>

        <div class="nav-right">
            @auth
                <div id="profileRoot" class="profile-root">
                    <button id="profileBtn" class="profile-btn" onclick="document.getElementById('profileRoot').classList.toggle('profile-open')">
                        <img src="{{ auth()->user()->profile_photo_url ?? asset('images/mushroom.png') }}" alt="avatar" class="profile-img">
                    </button>
                    <div class="profile-menu" aria-expanded="false">
                        <div style="padding:10px;border-bottom:1px solid #eef2f7;">
                            <div style="font-weight:700">{{ auth()->user()->name }}</div>
                            <div style="font-size:12px;color:var(--muted)">{{ auth()->user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" style="display:block;padding:10px 12px;color:#0f172a;text-decoration:none">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" style="display:block;width:100%;text-align:left;padding:10px 12px;border:none;background:transparent;cursor:pointer">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Login</a>
            @endauth
        </div>
    </header>

    <script>
        // close profile menu when clicking outside
        document.addEventListener('click', function(e){
            var root = document.getElementById('profileRoot');
            if(!root) return;
            if(!root.contains(e.target)) root.classList.remove('profile-open');
        });
    </script>
</div>

