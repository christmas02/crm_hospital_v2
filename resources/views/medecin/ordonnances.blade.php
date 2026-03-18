@extends('layouts.medicare')

@section('title', 'Ordonnances - MediCare Pro')
@section('sidebar-subtitle', 'Espace Médecin')
@section('user-color', '#7c3aed')
@section('header-title', 'Ordonnances émises')

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
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
            Ordonnances émises
        </h2>
        <span class="text-muted text-sm">{{ $ordonnances->total() }} ordonnances</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N° Retrait</th>
                        <th>Patient</th>
                        <th>Médicaments</th>
                        <th>Recommandations</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordonnances as $ordonnance)
                    <tr>
                        <td>{{ $ordonnance->date->format('d/m/Y') }}</td>
                        <td><code style="background:var(--gray-100);padding:2px 6px;border-radius:4px;">{{ $ordonnance->numero_retrait }}</code></td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($ordonnance->patient->prenom, 0, 1) . substr($ordonnance->patient->nom, 0, 1)) }}</div>
                                <span>{{ $ordonnance->patient->prenom }} {{ $ordonnance->patient->nom }}</span>
                            </div>
                        </td>
                        <td>
                            @if($ordonnance->medicaments->isNotEmpty())
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach($ordonnance->medicaments->take(2) as $med)
                                <span style="font-size:0.75rem;background:var(--primary-light);color:var(--primary);padding:2px 8px;border-radius:20px;">{{ $med->nom }}</span>
                                @endforeach
                                @if($ordonnance->medicaments->count() > 2)
                                <span style="font-size:0.75rem;background:var(--gray-100);color:var(--gray-600);padding:2px 8px;border-radius:20px;">+{{ $ordonnance->medicaments->count() - 2 }}</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted text-sm">—</span>
                            @endif
                        </td>
                        <td class="truncate" style="max-width:150px;">{{ $ordonnance->recommandations ?? '-' }}</td>
                        <td>
                            @php
                                $statusMap = [
                                    'en_attente' => ['warning', 'En attente'],
                                    'prepare'    => ['info', 'Préparée'],
                                    'remis'      => ['success', 'Remise'],
                                ];
                                $s = $statusMap[$ordonnance->statut_dispensation] ?? ['secondary', $ordonnance->statut_dispensation];
                            @endphp
                            <span class="badge badge-{{ $s[0] }}">{{ $s[1] }}</span>
                        </td>
                        <td>
                            <button class="btn btn-outline btn-sm" onclick="voirOrdonnance({{ $ordonnance->id }})">Voir</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding:60px;">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 16px;display:block;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                            <p style="color:var(--gray-500);">Aucune ordonnance émise</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($ordonnances->hasPages())
    <div class="card-body" style="border-top:1px solid var(--border);">
        {{ $ordonnances->links() }}
    </div>
    @endif
</div>

<!-- Modal détail ordonnance -->
<div id="modalOrdonnance" class="modal-overlay">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <h3 class="modal-title">Détail de l'ordonnance</h3>
            <button onclick="closeModal('modalOrdonnance')" class="modal-close">✕</button>
        </div>
        <div class="modal-body" id="modalOrdonnanceContent">
            @foreach($ordonnances as $ordonnance)
            <div id="ord-{{ $ordonnance->id }}" style="display:none;">
                <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
                    <div>
                        <div class="text-muted text-sm">Patient</div>
                        <strong>{{ $ordonnance->patient->prenom }} {{ $ordonnance->patient->nom }}</strong>
                    </div>
                    <div style="text-align:right;">
                        <div class="text-muted text-sm">Date</div>
                        <strong>{{ $ordonnance->date->format('d/m/Y') }}</strong>
                    </div>
                </div>

                <div style="background:var(--gray-50);border-radius:8px;padding:16px;margin-bottom:16px;">
                    <div class="text-muted text-sm mb-2">Médicaments prescrits</div>
                    @foreach($ordonnance->medicaments as $med)
                    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 60px;gap:8px;padding:8px 0;border-bottom:1px solid var(--border);">
                        <div><strong>{{ $med->nom }}</strong></div>
                        <div class="text-sm text-muted">{{ $med->posologie }}</div>
                        <div class="text-sm text-muted">{{ $med->duree }}</div>
                        <div class="text-sm">Qté: {{ $med->quantite }}</div>
                    </div>
                    @endforeach
                </div>

                @if($ordonnance->recommandations)
                <div>
                    <div class="text-muted text-sm mb-1">Recommandations</div>
                    <p>{{ $ordonnance->recommandations }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentOrdId = null;
function voirOrdonnance(id) {
    if (currentOrdId) document.getElementById('ord-' + currentOrdId).style.display = 'none';
    document.getElementById('ord-' + id).style.display = 'block';
    currentOrdId = id;
    openModal('modalOrdonnance');
}
</script>
@endpush

@endsection
