@extends('layouts.medicare')

@section('title', 'Historique mouvements - Pharmacie')
@section('sidebar-subtitle', 'Pharmacie')
@section('user-color', '#dc2626')
@section('header-title', 'Historique des mouvements')

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
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
            Historique mouvements
        </h2>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Médicament</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Motif</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mouvements as $mouvement)
                    <tr>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:middle;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            {{ \Carbon\Carbon::parse($mouvement->date)->format('d/m/Y') }}
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:middle;margin-left:6px;margin-right:4px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            {{ \Carbon\Carbon::parse($mouvement->date)->format('H:i') }}
                        </td>
                        <td>
                            <strong>{{ $mouvement->medicament->nom ?? '-' }}</strong>
                        </td>
                        <td>
                            @if($mouvement->type == 'entree')
                            <span class="badge badge-success">Entrée</span>
                            @else
                            <span class="badge badge-danger">Sortie</span>
                            @endif
                        </td>
                        <td>
                            <strong class="{{ $mouvement->type == 'entree' ? 'text-success' : 'text-danger' }}">
                                {{ $mouvement->type == 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
                            </strong>
                        </td>
                        <td>{{ $mouvement->motif ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucun mouvement enregistré</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Les entrées et sorties de stock apparaissent ici</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
