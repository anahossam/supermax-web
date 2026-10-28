<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    @include('partials.head')
</head>
<body>
<div class="sm-shell">
    <aside class="sm-sidebar d-none d-lg-flex">
        @include('partials.sidebar')
    </aside>

    <div class="sm-main">
        {{-- Phone top bar --}}
        <div class="sm-mobilebar d-lg-none">
            <button class="btn btn-link p-0 text-white" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sm-menu" aria-controls="sm-menu" aria-label="القائمة">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="sm-logo">SUPER<span>MAX</span></div>
            <span class="sm-avatar">{{ auth()->user()->initial() }}</span>
        </div>

        <header class="sm-pagehead">
            <div>
                <h1>@yield('title')</h1>
                @hasSection('subtitle')
                    <div class="sm-subtitle">@yield('subtitle')</div>
                @endif
            </div>
            <div class="d-flex flex-wrap gap-2">@yield('actions')</div>
        </header>

        <main class="sm-content">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @yield('content')
        </main>

        <footer class="sm-footer">
            {{ config('app.name') }} · الإصدار {{ config('app.version') }} · تصميم وتطوير {{ config('app.author') }}
        </footer>
    </div>
</div>

{{-- Phone menu: the same sidebar in an offcanvas panel --}}
<div class="offcanvas offcanvas-start sm-offcanvas d-lg-none d-flex flex-column" tabindex="-1" id="sm-menu" aria-label="القائمة">
    @include('partials.sidebar')
</div>

{{-- Phone bottom menu --}}
<nav class="sm-bottomnav d-lg-none">
    <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])><i class="bi bi-grid-1x2"></i>الرئيسية</a>
    <a href="{{ route('production-orders.index') }}" @class(['active' => request()->routeIs('production-orders.*')])><i class="bi bi-gear"></i>التشغيل</a>
    <a href="{{ route('maintenance-orders.index') }}" @class(['active' => request()->routeIs('maintenance-orders.*')])><i class="bi bi-wrench"></i>الصيانة</a>
    <a href="{{ route('customers.index') }}" @class(['active' => request()->routeIs('customers.*')])><i class="bi bi-people"></i>العملاء</a>
</nav>

@can('edit')
    <a href="@yield('fab', route('production-orders.index'))" class="sm-fab d-lg-none" aria-label="إضافة"><i class="bi bi-plus-lg"></i></a>
@endcan
</body>
</html>
