@extends('layouts.medicare')

@section('title', 'Fiches de traitement - MediCare Pro')
@section('sidebar-subtitle', 'Espace Médecin')
@section('user-color', '#7c3aed')
@section('header-title', 'Fiches de traitement')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('medecin._sidebar')
@endif
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h6"/></svg>
            Fiches de traitement
        </h2>
        <span class="text-muted text-sm">{{ $fiches->total() }} fiches au total</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Diagnostic</th>
                        <th>Actes réalisés</th>
                        <th>Total facturable</th>
                        <th>Facture</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fiches as $fiche)
                    <tr>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            {{ $fiche->date->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($fiche->patient->prenom, 0, 1) . substr($fiche->patient->nom, 0, 1)) }}</div>
                                <span>{{ $fiche->patient->prenom }} {{ $fiche->patient->nom }}</span>
                            </div>
                        </td>
                        <td class="truncate" style="max-width:200px;">{{ $fiche->diagnostic ?? '-' }}</td>
                        <td>
                            @if($fiche->actesMedicaux->isNotEmpty())
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach($fiche->actesMedicaux->take(2) as $acte)
                                <span style="font-size:0.75rem;background:var(--primary-light);color:var(--primary);padding:2px 8px;border-radius:20px;">{{ $acte->nom }}</span>
                                @endforeach
                                @if($fiche->actesMedicaux->count() > 2)
                                <span style="font-size:0.75rem;background:var(--gray-100);color:var(--gray-600);padding:2px 8px;border-radius:20px;">+{{ $fiche->actesMedicaux->count() - 2 }}</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted text-sm">Aucun acte</span>
                            @endif
                        </td>
                        <td>
                            <strong class="{{ $fiche->total_facturable > 0 ? 'text-success' : 'text-muted' }}">
                                {{ number_format($fiche->total_facturable ?? 0, 0, ',', ' ') }} F
                            </strong>
                        </td>
                        <td>
                            @if($fiche->facture)
                            <span class="badge badge-{{ $fiche->facture->statut === 'paye' ? 'success' : 'warning' }}">
                                {{ $fiche->facture->statut === 'paye' ? 'Payée' : 'En attente' }}
                            </span>
                            @else
                            <span class="text-muted text-sm">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('medecin.consultations.show', $fiche->consultation_id) }}" class="btn btn-primary btn-sm"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune fiche de traitement</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Les fiches apparaitront après les consultations</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($fiches->hasPages())
    <div class="card-body" style="border-top:1px solid var(--border);">
        {{ $fiches->links() }}
    </div>
    @endif
</div>

<!-- Modal détail fiche -->
<div id="modalFiche" class="modal-overlay">
    <div class="modal" style="max-width:600px;">
        <div class="modal-header">
            <h3 class="modal-title">Détail de la fiche</h3>
            <button onclick="closeModal('modalFiche')" class="modal-close">✕</button>
        </div>
        <div class="modal-body" id="modalFicheContent"></div>
    </div>
</div>

@endsection
