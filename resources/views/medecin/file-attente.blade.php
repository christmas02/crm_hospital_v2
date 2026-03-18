@extends('layouts.medicare')

@section('title', 'File d\'attente - MediCare Pro')
@section('sidebar-subtitle', 'Espace Médecin')
@section('user-color', '#7c3aed')
@section('header-title', 'File d\'attente')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('medecin._sidebar')
@endif
@endsection

@section('content')

@if($consultationEnCours)
<div class="card mb-4" style="border:2px solid var(--primary);background:var(--primary-light);">
    <div class="card-body" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div class="avatar lg" style="background:var(--primary);color:#fff;">
                {{ strtoupper(substr($consultationEnCours->patient->prenom, 0, 1) . substr($consultationEnCours->patient->nom, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:600;font-size:1.1rem;">{{ $consultationEnCours->patient->prenom }} {{ $consultationEnCours->patient->nom }}</div>
                <div class="text-muted text-sm">Consultation en cours · {{ $consultationEnCours->motif }}</div>
            </div>
        </div>
        <a href="{{ route('medecin.consultations.show', $consultationEnCours) }}" class="btn btn-primary">
            Reprendre la consultation
        </a>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Patients en attente
        </h2>
        <span class="badge badge-warning">{{ $consultations->count() }}</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Heure</th>
                        <th>Patient</th>
                        <th>Âge</th>
                        <th>Motif</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $i => $consultation)
                    <tr>
                        <td>
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;background:{{ $i === 0 ? 'var(--primary)' : 'var(--gray-200)' }};color:{{ $i === 0 ? '#fff' : 'var(--gray-700)' }};border-radius:50%;font-weight:600;font-size:0.875rem;">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            {{ $consultation->heure }}
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</div>
                                    <div class="user-sub">{{ $consultation->patient->telephone }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            {{ $consultation->patient->date_naissance ? \Carbon\Carbon::parse($consultation->patient->date_naissance)->age . ' ans' : '-' }}
                        </td>
                        <td class="truncate" style="max-width:180px;">{{ $consultation->motif }}</td>
                        <td>
                            <span class="badge {{ $consultation->type === 'urgence' ? 'badge-danger' : 'badge-info' }}">
                                {{ ucfirst($consultation->type ?? 'standard') }}
                            </span>
                        </td>
                        <td>
                            @if(!$consultationEnCours)
                            <form action="{{ route('medecin.consultations.start', $consultation) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    Appeler
                                </button>
                            </form>
                            @else
                            <span class="text-muted text-sm">En attente</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucun patient en attente</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">La file d'attente est vide pour le moment</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
