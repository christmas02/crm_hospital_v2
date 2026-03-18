@extends('layouts.medicare')

@section('title', 'Analytiques - MediCare Pro')
@section('sidebar-subtitle', 'Administration')
@section('header-title', 'Tableau de bord analytique')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

<!-- Period filter -->
<div class="toolbar">
    <div class="filters">
        <a href="{{ route('admin.analytics', ['period' => 7]) }}" class="btn {{ $period == 7 ? 'btn-primary' : 'btn-outline' }} btn-sm">7 jours</a>
        <a href="{{ route('admin.analytics', ['period' => 30]) }}" class="btn {{ $period == 30 ? 'btn-primary' : 'btn-outline' }} btn-sm">30 jours</a>
        <a href="{{ route('admin.analytics', ['period' => 90]) }}" class="btn {{ $period == 90 ? 'btn-primary' : 'btn-outline' }} btn-sm">90 jours</a>
        <a href="{{ route('admin.analytics', ['period' => 365]) }}" class="btn {{ $period == 365 ? 'btn-primary' : 'btn-outline' }} btn-sm">1 an</a>
    </div>
</div>

<!-- KPIs -->
<div class="stats" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card" style="border-left:4px solid var(--primary);">
        <div>
            <div class="stat-label">Patients total</div>
            <div class="stat-value">{{ $kpis['patients_total'] }}</div>
            <div class="stat-sub">+{{ $kpis['patients_mois'] }} ce mois</div>
        </div>
        <div class="stat-icon cyan"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--secondary);">
        <div>
            <div class="stat-label">Consultations</div>
            <div class="stat-value">{{ $kpis['consultations_total'] }}</div>
            <div class="stat-sub">{{ $kpis['consultations_terminees'] }} terminées</div>
        </div>
        <div class="stat-icon green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--success);">
        <div>
            <div class="stat-label">Recettes</div>
            <div class="stat-value" style="font-size:1.3rem;">{{ number_format($kpis['recettes_periode'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">dépenses: {{ number_format($kpis['depenses_periode'], 0, ',', ' ') }} F</div>
        </div>
        <div class="stat-icon green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--warning);">
        <div>
            <div class="stat-label">Occupation chambres</div>
            <div class="stat-value">{{ $kpis['taux_occupation'] }}%</div>
            <div class="stat-sub">{{ $kpis['medicaments_alerte'] }} alertes stock</div>
        </div>
        <div class="stat-icon orange"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="grid-2 mt-4">
    <div class="card">
        <div class="card-header"><h2 class="card-title">Consultations</h2></div>
        <div class="card-body"><div class="chart-container" style="height:280px;"><canvas id="chartConsult"></canvas></div></div>
    </div>
    <div class="card">
        <div class="card-header"><h2 class="card-title">Recettes (F CFA)</h2></div>
        <div class="card-body"><div class="chart-container" style="height:280px;"><canvas id="chartRecettes"></canvas></div></div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="grid-2 mt-4">
    <div class="card">
        <div class="card-header"><h2 class="card-title">Nouveaux patients (12 mois)</h2></div>
        <div class="card-body"><div class="chart-container" style="height:280px;"><canvas id="chartPatients"></canvas></div></div>
    </div>
    <div class="card">
        <div class="card-header"><h2 class="card-title">Répartition par spécialité</h2></div>
        <div class="card-body"><div class="chart-container" style="height:280px;"><canvas id="chartSpecialite"></canvas></div></div>
    </div>
</div>

<!-- Top Medecins -->
<div class="card mt-4">
    <div class="card-header"><h2 class="card-title">Top 5 médecins (par consultations)</h2></div>
    <div class="card-body no-pad">
        <table class="table-patients">
            <thead><tr><th>Médecin</th><th>Spécialité</th><th>Consultations</th><th>Performance</th></tr></thead>
            <tbody>
                @foreach($topMedecins as $i => $med)
                @php $maxC = $topMedecins->first()->consultations_count ?: 1; @endphp
                <tr>
                    <td><div class="user-cell"><div class="avatar" style="background:var(--primary-light);color:var(--primary);">{{ strtoupper(substr($med->prenom,0,1).substr($med->nom,0,1)) }}</div><div class="user-name">Dr. {{ $med->prenom }} {{ $med->nom }}</div></div></td>
                    <td>{{ $med->specialite }}</td>
                    <td style="font-weight:700;">{{ $med->consultations_count }}</td>
                    <td style="width:200px;"><div style="width:100%;height:8px;background:var(--gray-100);border-radius:4px;overflow:hidden;"><div style="width:{{ ($med->consultations_count / $maxC) * 100 }}%;height:100%;background:linear-gradient(90deg,var(--primary),var(--secondary));border-radius:4px;"></div></div></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Consultations line chart
    new Chart(document.getElementById('chartConsult'), {
        type: 'line',
        data: {
            labels: @json($consultationsParJour->pluck('date')),
            datasets: [{
                label: 'Consultations',
                data: @json($consultationsParJour->pluck('count')),
                borderColor: 'rgb(8,145,178)',
                backgroundColor: 'rgba(8,145,178,0.1)',
                fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });

    // Recettes bar chart
    new Chart(document.getElementById('chartRecettes'), {
        type: 'bar',
        data: {
            labels: @json($recettesParJour->pluck('date')),
            datasets: [{
                label: 'Recettes',
                data: @json($recettesParJour->pluck('montant')),
                backgroundColor: 'rgba(5,150,105,0.7)',
                borderRadius: 6,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // Patients bar chart
    new Chart(document.getElementById('chartPatients'), {
        type: 'bar',
        data: {
            labels: @json($patientsMensuels->pluck('mois')),
            datasets: [{
                label: 'Nouveaux patients',
                data: @json($patientsMensuels->pluck('count')),
                backgroundColor: 'rgba(8,145,178,0.7)',
                borderRadius: 6,
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });

    // Specialite doughnut
    new Chart(document.getElementById('chartSpecialite'), {
        type: 'doughnut',
        data: {
            labels: @json($parSpecialite->keys()),
            datasets: [{
                data: @json($parSpecialite->values()),
                backgroundColor: ['rgb(8,145,178)', 'rgb(5,150,105)', 'rgb(217,119,6)', 'rgb(124,58,237)', 'rgb(220,38,38)', 'rgb(236,72,153)'],
                borderWidth: 2, borderColor: '#fff',
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '55%', plugins: { legend: { position: 'right', labels: { padding: 12, usePointStyle: true } } } }
    });
});
</script>
@endpush

@endsection
