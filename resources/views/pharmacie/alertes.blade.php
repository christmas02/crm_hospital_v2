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
        <h2 class="card-title">Médicaments en stock critique</h2>
    </div>
    <div class="card-body">
        @if($alertes->count() > 0)
        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:16px;">
            @foreach($alertes as $medicament)
            <div style="background:#fff;border:2px solid {{ $medicament->stock <= 0 ? 'var(--danger)' : 'var(--warning)' }};border-radius:12px;padding:16px;">
                <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px;">
                    <div>
                        <div style="font-weight:600;font-size:1.1rem;">{{ $medicament->nom }}</div>
                        <div class="text-muted">{{ $medicament->forme ?? 'Comprimé' }} - {{ $medicament->categorie ?? '' }}</div>
                    </div>
                    @if($medicament->stock <= 0)
                    <span class="badge badge-danger">Rupture</span>
                    @else
                    <span class="badge badge-warning">Stock bas</span>
                    @endif
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:1px solid var(--border);">
                    <div>
                        <div class="text-sm text-muted">Stock actuel</div>
                        <div style="font-size:1.5rem;font-weight:bold;color:{{ $medicament->stock <= 0 ? 'var(--danger)' : 'var(--warning)' }};">{{ $medicament->stock }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div class="text-sm text-muted">Minimum requis</div>
                        <div style="font-size:1.2rem;font-weight:600;">{{ $medicament->stock_min }}</div>
                    </div>
                </div>
                <div style="margin-top:12px;">
                    <a href="{{ route('pharmacie.approvisionnements') }}" class="btn btn-success btn-sm" style="width:100%;">Commander</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center" style="padding:60px;">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1.5" style="margin:0 auto 20px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
            <h3 style="color:var(--success);margin-bottom:8px;">Aucune alerte</h3>
            <p class="text-muted">Tous les médicaments ont un stock suffisant</p>
        </div>
        @endif
    </div>
</div>
@endsection
