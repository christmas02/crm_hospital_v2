@extends('layouts.medicare')

@section('title', 'Rappels de rendez-vous - MediCare Pro')
@section('sidebar-subtitle', 'Administration')
@section('user-color', '#7c3aed')
@section('header-title', 'Rappels de rendez-vous')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')
<div class="toolbar">
    <div class="filters">
        <span class="text-muted">Gestion des rappels automatiques et manuels</span>
    </div>
    <form action="{{ route('admin.rappels-rdv.envoyer') }}" method="POST" style="display:inline;">
        @csrf
        <input type="hidden" name="date" value="{{ now()->addDay()->format('Y-m-d') }}">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Envoyer tous les rappels pour demain ?')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            Envoyer tous les rappels pour demain
        </button>
    </form>
</div>

@php
    $demainAvecEmail = $consultationsDemain->filter(fn($c) => $c->patient->email)->count();
    $demainSansEmail = $consultationsDemain->count() - $demainAvecEmail;
    $aujourdhuiAvecEmail = $consultationsAujourdhui->filter(fn($c) => $c->patient->email)->count();
    $aujourdhuiSansEmail = $consultationsAujourdhui->count() - $aujourdhuiAvecEmail;
@endphp

<!-- Stats -->
<div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="card" style="padding:20px;text-align:center;">
        <div style="font-size:2rem;font-weight:700;color:var(--primary);">{{ $consultationsDemain->count() }}</div>
        <div class="text-muted" style="font-size:.85rem;">RDV demain</div>
    </div>
    <div class="card" style="padding:20px;text-align:center;">
        <div style="font-size:2rem;font-weight:700;color:#059669;">{{ $demainAvecEmail }}</div>
        <div class="text-muted" style="font-size:.85rem;">Avec email (demain)</div>
    </div>
    <div class="card" style="padding:20px;text-align:center;">
        <div style="font-size:2rem;font-weight:700;color:#dc2626;">{{ $demainSansEmail }}</div>
        <div class="text-muted" style="font-size:.85rem;">Sans email (demain)</div>
    </div>
    <div class="card" style="padding:20px;text-align:center;">
        <div style="font-size:2rem;font-weight:700;color:var(--warning);">{{ $consultationsAujourdhui->count() }}</div>
        <div class="text-muted" style="font-size:.85rem;">RDV aujourd'hui</div>
    </div>
</div>

<!-- RDV Demain -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            Rendez-vous de demain ({{ now()->addDay()->format('d/m/Y') }})
        </h2>
        <span class="text-muted text-sm">{{ $consultationsDemain->count() }} rendez-vous</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Medecin</th>
                        <th>Motif</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultationsDemain as $consultation)
                    <tr>
                        <td><span style="font-weight:500;">{{ $consultation->heure }}</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="background:{{ $consultation->patient->sexe == 'M' ? 'var(--primary-light)' : '#fce7f3' }};color:{{ $consultation->patient->sexe == 'M' ? 'var(--primary)' : '#db2777' }};width:32px;height:32px;font-size:.7rem;">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                <span>{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</span>
                            </div>
                        </td>
                        <td>
                            @if($consultation->patient->email)
                                <span style="color:#059669;font-size:.85rem;">{{ $consultation->patient->email }}</span>
                            @else
                                <span class="text-muted" style="font-size:.85rem;">Aucun email</span>
                            @endif
                        </td>
                        <td>Dr. {{ $consultation->medecin->prenom }} {{ $consultation->medecin->nom }}</td>
                        <td class="truncate" style="max-width:180px;">{{ $consultation->motif }}</td>
                        <td style="text-align:center;">
                            @if($consultation->patient->email)
                            <form action="{{ route('reception.consultations.rappel', $consultation) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:var(--warning-light);color:var(--warning);border:1px solid var(--warning);" title="Envoyer rappel">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                                    Rappel
                                </button>
                            </form>
                            @else
                            <span class="badge badge-danger" style="font-size:.7rem;">Pas d'email</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:32px;">
                            <div class="text-muted" style="font-size:.875rem;">Aucun rendez-vous pour demain</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- RDV Aujourd'hui -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Rendez-vous d'aujourd'hui ({{ now()->format('d/m/Y') }})
        </h2>
        <span class="text-muted text-sm">{{ $consultationsAujourdhui->count() }} rendez-vous en attente</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Medecin</th>
                        <th>Motif</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultationsAujourdhui as $consultation)
                    <tr>
                        <td><span style="font-weight:500;">{{ $consultation->heure }}</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="background:{{ $consultation->patient->sexe == 'M' ? 'var(--primary-light)' : '#fce7f3' }};color:{{ $consultation->patient->sexe == 'M' ? 'var(--primary)' : '#db2777' }};width:32px;height:32px;font-size:.7rem;">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                <span>{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</span>
                            </div>
                        </td>
                        <td>
                            @if($consultation->patient->email)
                                <span style="color:#059669;font-size:.85rem;">{{ $consultation->patient->email }}</span>
                            @else
                                <span class="text-muted" style="font-size:.85rem;">Aucun email</span>
                            @endif
                        </td>
                        <td>Dr. {{ $consultation->medecin->prenom }} {{ $consultation->medecin->nom }}</td>
                        <td class="truncate" style="max-width:180px;">{{ $consultation->motif }}</td>
                        <td style="text-align:center;">
                            @if($consultation->patient->email)
                            <form action="{{ route('reception.consultations.rappel', $consultation) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:var(--warning-light);color:var(--warning);border:1px solid var(--warning);" title="Envoyer rappel">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                                    Rappel
                                </button>
                            </form>
                            @else
                            <span class="badge badge-danger" style="font-size:.7rem;">Pas d'email</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:32px;">
                            <div class="text-muted" style="font-size:.875rem;">Aucun rendez-vous en attente aujourd'hui</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Info -->
<div class="card" style="margin-top:24px;padding:20px;">
    <h3 style="font-size:.95rem;font-weight:600;margin-bottom:12px;">Commande artisan pour rappels automatiques</h3>
    <div style="background:var(--gray-50);border-radius:8px;padding:16px;font-family:monospace;font-size:.85rem;color:var(--gray-700);">
        <div>php artisan rappels:envoyer</div>
        <div style="color:var(--gray-400);margin-top:4px;">// Envoie les rappels pour les RDV de demain (par defaut)</div>
        <div style="margin-top:8px;">php artisan rappels:envoyer --jours=2</div>
        <div style="color:var(--gray-400);margin-top:4px;">// Envoie les rappels pour les RDV dans 2 jours</div>
    </div>
    <p class="text-muted" style="font-size:.8rem;margin-top:12px;">Ajoutez cette commande au cron (scheduler) pour automatiser l'envoi quotidien des rappels.</p>
</div>
@endsection
