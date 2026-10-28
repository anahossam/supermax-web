@extends('layouts.app')

@php
    $stats = [
        ['label' => 'إجمالي أوامر التشغيل', 'value' => \App\Models\ProductionOrder::count(), 'icon' => 'bi-gear', 'tone' => 'warning'],
        ['label' => 'تحت التشغيل الآن', 'value' => \App\Models\ProductionOrder::inProduction()->count(), 'icon' => 'bi-play-fill', 'tone' => 'primary'],
        ['label' => 'أوامر الصيانة', 'value' => \App\Models\MaintenanceOrder::count(), 'icon' => 'bi-wrench', 'tone' => 'secondary'],
        ['label' => 'العملاء', 'value' => \App\Models\Customer::count(), 'icon' => 'bi-people', 'tone' => 'success'],
    ];
@endphp

@section('title', 'الرئيسية')
@section('subtitle', now()->translatedFormat('l').' '.now()->format('d/m/Y'))

@section('content')
    <div class="row g-3">
        @foreach ($stats as $stat)
            <div class="col-6 col-xl-3">
                <div class="sm-card sm-stat d-flex justify-content-between align-items-start h-100">
                    <div>
                        <div class="text-muted small mb-2">{{ $stat['label'] }}</div>
                        <div class="sm-stat-value">{{ number_format($stat['value']) }}</div>
                    </div>
                    <span class="sm-stat-icon bg-{{ $stat['tone'] }}-subtle text-{{ $stat['tone'] }}"><i class="bi {{ $stat['icon'] }}"></i></span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="sm-card p-4 mt-3 text-muted">
        <i class="bi bi-info-circle"></i>
        آخر الأوامر والرسم البياني والإجراءات السريعة ستُضاف في المرحلة 4.
    </div>
@endsection
