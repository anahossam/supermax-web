@php
    $links = [
        ['route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'bi-grid-1x2', 'label' => 'الرئيسية'],
        ['route' => 'production-orders.index', 'match' => 'production-orders.*', 'icon' => 'bi-gear', 'label' => 'أوامر التشغيل'],
        ['route' => 'maintenance-orders.index', 'match' => 'maintenance-orders.*', 'icon' => 'bi-wrench', 'label' => 'أوامر الصيانة'],
        ['route' => 'customers.index', 'match' => 'customers.*', 'icon' => 'bi-people', 'label' => 'العملاء'],
    ];
    $adminLinks = [
        ['route' => 'lists.index', 'match' => 'lists.*', 'icon' => 'bi-list-ul', 'label' => 'القوائم'],
        ['route' => 'users.index', 'match' => 'users.*', 'icon' => 'bi-key', 'label' => 'المستخدمون'],
        ['route' => 'audit.index', 'match' => 'audit.*', 'icon' => 'bi-clock-history', 'label' => 'سجل التعديلات'],
    ];
@endphp

<div class="sm-brand">
    <div class="sm-logo">SUPER<span>MAX</span></div>
    <small>للصناعات المعدنية · نظام أوامر التشغيل</small>
</div>

<nav class="sm-nav">
    @foreach ($links as $link)
        <a href="{{ route($link['route']) }}" @class(['sm-nav-link', 'active' => request()->routeIs($link['match'])])>
            <i class="bi {{ $link['icon'] }}"></i> {{ $link['label'] }}
        </a>
    @endforeach

    @can('admin')
        <div class="sm-nav-label">الإعدادات</div>
        @foreach ($adminLinks as $link)
            <a href="{{ route($link['route']) }}" @class(['sm-nav-link', 'active' => request()->routeIs($link['match'])])>
                <i class="bi {{ $link['icon'] }}"></i> {{ $link['label'] }}
            </a>
        @endforeach
    @endcan
</nav>

<div class="sm-user">
    <span class="sm-avatar">{{ auth()->user()->initial() }}</span>
    <div class="flex-grow-1 min-w-0">
        <div class="sm-user-name text-truncate">{{ auth()->user()->name }}</div>
        <div class="sm-user-role">{{ auth()->user()->role->label() }}</div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 text-white-50" title="تسجيل الخروج" aria-label="تسجيل الخروج">
            <i class="bi bi-box-arrow-left fs-5"></i>
        </button>
    </form>
</div>
<div class="sm-credit" dir="ltr">v{{ config('app.version') }} · {{ config('app.author') }}</div>
