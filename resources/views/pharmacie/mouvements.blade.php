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
        <h2 class="card-title">Historique mouvements</h2>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
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
                        <td>{{ \Carbon\Carbon::parse($mouvement->date)->format('d/m/Y H:i') }}</td>
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
                    <tr><td colspan="5" class="text-center text-muted">Aucun mouvement enregistré</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
