@extends('layouts.medicare')

@section('title', 'Gestion du Stock - MediCare Pro')
@section('sidebar-subtitle', 'Pharmacie')
@section('user-color', '#ec4899')
@section('header-title', 'Gestion du Stock')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('pharmacie._sidebar')
@endif
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success mb-4" style="background:#d1fae5;border:1px solid #10b981;color:#065f46;padding:12px 16px;border-radius:8px;">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Liste des médicaments</h2>
        <span class="text-muted text-sm">{{ $medicaments->total() }} médicaments</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Médicament</th>
                        <th>Forme</th>
                        <th>Dosage</th>
                        <th style="text-align:right;">Prix</th>
                        <th style="text-align:center;">Stock</th>
                        <th style="text-align:center;">Stock Min</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicaments as $medicament)
                    <tr style="{{ $medicament->stock <= $medicament->stock_min ? 'background:rgba(239,68,68,0.1);' : '' }}">
                        <td><strong>{{ $medicament->nom }}</strong></td>
                        <td>{{ $medicament->forme }}</td>
                        <td>{{ $medicament->dosage }}</td>
                        <td style="text-align:right;">{{ number_format($medicament->prix, 0, ',', ' ') }} F</td>
                        <td style="text-align:center;">
                            <span style="font-weight:bold;color:{{ $medicament->stock <= $medicament->stock_min ? '#ef4444' : ($medicament->stock <= $medicament->stock_min * 2 ? '#f59e0b' : '#10b981') }};">
                                {{ $medicament->stock }}
                            </span>
                        </td>
                        <td style="text-align:center;" class="text-muted">{{ $medicament->stock_min }}</td>
                        <td>
                            @if($medicament->stock <= $medicament->stock_min)
                            <span class="badge badge-danger">Stock bas</span>
                            @elseif($medicament->stock <= $medicament->stock_min * 2)
                            <span class="badge badge-warning">Attention</span>
                            @else
                            <span class="badge badge-success">OK</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pharmacie.stock.show', $medicament) }}" class="btn btn-outline btn-sm">Gérer</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($medicaments->hasPages())
<div class="mt-4 flex justify-center">
    {{ $medicaments->links() }}
</div>
@endif
@endsection
