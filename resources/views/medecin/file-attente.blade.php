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
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Patients en attente
        </h2>
        <span class="badge badge-warning">{{ $consultations->count() }}</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
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
                        <td>{{ $consultation->heure }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</div>
                                    <div class="user-sub">{{ $consultation->patient->telephone }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $consultation->patient->date_naissance ? \Carbon\Carbon::parse($consultation->patient->date_naissance)->age . ' ans' : '-' }}</td>
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
                        <td colspan="7" class="text-center" style="padding:60px;">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 16px;display:block;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <p style="color:var(--gray-500);">Aucun patient en attente</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
