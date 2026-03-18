@extends('layouts.medicare')

@section('title', 'Alertes stock - Pharmacie')
@section('sidebar-subtitle', 'Pharmacie')
@section('user-color', '#dc2626')
@section('header-title', 'Alertes stock')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('pharmacie._sidebar')
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
            Médicaments en stock critique
        </h2>
        <span class="badge badge-danger">{{ $alertes->count() }} alertes</span>
    </div>
    <div class="card-body">
        @if($alertes->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:20px;">
            @foreach($alertes as $medicament)
            @php
                $isRupture = $medicament->stock <= 0;
                $pct = $medicament->stock_min > 0 ? min(($medicament->stock / $medicament->stock_min) * 100, 100) : 0;
            @endphp
            <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.06);border:1px solid var(--gray-200);transition:all .2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.06)'">
                <!-- Top colored bar -->
                <div style="height:4px;background:{{ $isRupture ? 'linear-gradient(90deg, var(--danger), #f87171)' : 'linear-gradient(90deg, var(--warning), #fbbf24)' }};"></div>

                <div style="padding:20px;">
                    <!-- Header -->
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:44px;height:44px;border-radius:12px;background:{{ $isRupture ? 'var(--danger-light)' : 'var(--warning-light)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="{{ $isRupture ? 'var(--danger)' : 'var(--warning)' }}" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:1rem;color:var(--gray-800);">{{ $medicament->nom }}</div>
                                <div style="font-size:.78rem;color:var(--gray-500);">{{ $medicament->forme ?? 'Comprimé' }} &bull; {{ $medicament->categorie ?? '' }}</div>
                            </div>
                        </div>
                        @if($isRupture)
                        <span class="badge badge-danger" style="font-size:.7rem;padding:5px 12px;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:3px;"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                            Rupture
                        </span>
                        @else
                        <span class="badge badge-warning" style="font-size:.7rem;padding:5px 12px;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:3px;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
                            Stock bas
                        </span>
                        @endif
                    </div>

                    <!-- Gauge bar -->
                    <div style="margin-bottom:16px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                            <span style="font-size:.72rem;color:var(--gray-500);font-weight:600;text-transform:uppercase;letter-spacing:.3px;">Niveau de stock</span>
                            <span style="font-size:.72rem;font-weight:700;color:{{ $isRupture ? 'var(--danger)' : 'var(--warning)' }};">{{ round($pct) }}%</span>
                        </div>
                        <div style="width:100%;height:8px;background:var(--gray-100);border-radius:4px;overflow:hidden;">
                            <div style="width:{{ $pct }}%;height:100%;border-radius:4px;background:{{ $isRupture ? 'linear-gradient(90deg, var(--danger), #f87171)' : 'linear-gradient(90deg, var(--warning), #fbbf24)' }};transition:width .5s ease;"></div>
                        </div>
                    </div>

                    <!-- Stock numbers -->
                    <div style="display:flex;gap:12px;margin-bottom:16px;">
                        <div style="flex:1;background:{{ $isRupture ? 'var(--danger-light)' : 'var(--warning-light)' }};padding:12px;border-radius:10px;text-align:center;">
                            <div style="font-size:.68rem;color:var(--gray-500);font-weight:600;text-transform:uppercase;letter-spacing:.3px;margin-bottom:4px;">Stock actuel</div>
                            <div style="font-size:1.5rem;font-weight:800;color:{{ $isRupture ? 'var(--danger)' : 'var(--warning)' }};">{{ $medicament->stock }}</div>
                        </div>
                        <div style="flex:1;background:var(--gray-50);padding:12px;border-radius:10px;text-align:center;">
                            <div style="font-size:.68rem;color:var(--gray-500);font-weight:600;text-transform:uppercase;letter-spacing:.3px;margin-bottom:4px;">Seuil minimum</div>
                            <div style="font-size:1.5rem;font-weight:800;color:var(--gray-700);">{{ $medicament->stock_min }}</div>
                        </div>
                    </div>

                    <!-- Action -->
                    <a href="{{ route('pharmacie.approvisionnements') }}" class="btn {{ $isRupture ? 'btn-danger' : 'btn-primary' }}" style="width:100%;padding:11px;border-radius:10px;font-weight:600;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        Commander
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:60px 20px;">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--success-light);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
            </div>
            <div style="font-size:1.1rem;font-weight:700;color:var(--gray-700);margin-bottom:6px;">Tout est en ordre !</div>
            <div style="font-size:.875rem;color:var(--gray-500);">Aucune alerte de stock. Tous les médicaments ont un stock suffisant.</div>
        </div>
        @endif
    </div>
</div>
@endsection
