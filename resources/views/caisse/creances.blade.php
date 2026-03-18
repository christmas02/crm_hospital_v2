@extends('layouts.medicare')

@section('title', 'Creances - MediCare Pro')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Rapport de creances')

@section('header-right')
<span class="text-muted">{{ $totaux['count'] }} facture(s) impayee(s)</span>
@endsection

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@push('styles')
<style>
.aging-card {
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 1px solid var(--border);
    background: #fff;
}
.aging-card .aging-label {
    font-size: .75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}
.aging-card .aging-value {
    font-size: 1.4rem;
    font-weight: 700;
}
.aging-card .aging-count {
    font-size: .75rem;
    margin-top: 4px;
}
.tranche-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 600;
}
.tranche-0-30 { background: #dcfce7; color: #166534; }
.tranche-31-60 { background: #fef3c7; color: #92400e; }
.tranche-61-90 { background: #fed7aa; color: #9a3412; }
.tranche-90plus { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<!-- Aging Cards -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="aging-card" style="border-left:4px solid #16a34a;">
        <div class="aging-label" style="color:#16a34a;">0 - 30 jours</div>
        <div class="aging-value" style="color:#16a34a;">{{ number_format($totaux['0-30'], 0, ',', ' ') }} F</div>
        <div class="aging-count text-muted">{{ $parTranche['0-30']->count() }} facture(s)</div>
    </div>
    <div class="aging-card" style="border-left:4px solid #d97706;">
        <div class="aging-label" style="color:#d97706;">31 - 60 jours</div>
        <div class="aging-value" style="color:#d97706;">{{ number_format($totaux['31-60'], 0, ',', ' ') }} F</div>
        <div class="aging-count text-muted">{{ $parTranche['31-60']->count() }} facture(s)</div>
    </div>
    <div class="aging-card" style="border-left:4px solid #ea580c;">
        <div class="aging-label" style="color:#ea580c;">61 - 90 jours</div>
        <div class="aging-value" style="color:#ea580c;">{{ number_format($totaux['61-90'], 0, ',', ' ') }} F</div>
        <div class="aging-count text-muted">{{ $parTranche['61-90']->count() }} facture(s)</div>
    </div>
    <div class="aging-card" style="border-left:4px solid #dc2626;">
        <div class="aging-label" style="color:#dc2626;">90+ jours</div>
        <div class="aging-value" style="color:#dc2626;">{{ number_format($totaux['90+'], 0, ',', ' ') }} F</div>
        <div class="aging-count text-muted">{{ $parTranche['90+']->count() }} facture(s)</div>
    </div>
</div>

<div class="grid-2" style="margin-bottom:24px;">
    <!-- Doughnut Chart -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Repartition par tranche</h2>
        </div>
        <div class="card-body" style="display:flex;justify-content:center;align-items:center;padding:24px;">
            <canvas id="agingChart" style="max-width:300px;max-height:300px;"></canvas>
        </div>
    </div>

    <!-- Summary -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Resume</h2>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:16px;">
                <div style="text-align:center;padding:20px;background:var(--gray-50);border-radius:8px;">
                    <div class="text-muted text-sm" style="margin-bottom:8px;">Total des creances</div>
                    <div style="font-size:2rem;font-weight:700;color:#dc2626;">{{ number_format($totaux['total'], 0, ',', ' ') }} F</div>
                    <div class="text-muted text-sm" style="margin-top:4px;">{{ $totaux['count'] }} facture(s) impayee(s)</div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div style="padding:12px;background:#f0fdf4;border-radius:8px;text-align:center;">
                        <div style="font-size:.7rem;color:#16a34a;text-transform:uppercase;letter-spacing:1px;">0-30j</div>
                        <div style="font-weight:700;color:#16a34a;">{{ $totaux['total'] > 0 ? round($totaux['0-30'] / $totaux['total'] * 100) : 0 }}%</div>
                    </div>
                    <div style="padding:12px;background:#fef3c7;border-radius:8px;text-align:center;">
                        <div style="font-size:.7rem;color:#92400e;text-transform:uppercase;letter-spacing:1px;">31-60j</div>
                        <div style="font-weight:700;color:#92400e;">{{ $totaux['total'] > 0 ? round($totaux['31-60'] / $totaux['total'] * 100) : 0 }}%</div>
                    </div>
                    <div style="padding:12px;background:#fed7aa;border-radius:8px;text-align:center;">
                        <div style="font-size:.7rem;color:#9a3412;text-transform:uppercase;letter-spacing:1px;">61-90j</div>
                        <div style="font-weight:700;color:#9a3412;">{{ $totaux['total'] > 0 ? round($totaux['61-90'] / $totaux['total'] * 100) : 0 }}%</div>
                    </div>
                    <div style="padding:12px;background:#fee2e2;border-radius:8px;text-align:center;">
                        <div style="font-size:.7rem;color:#991b1b;text-transform:uppercase;letter-spacing:1px;">90+j</div>
                        <div style="font-weight:700;color:#991b1b;">{{ $totaux['total'] > 0 ? round($totaux['90+'] / $totaux['total'] * 100) : 0 }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table of unpaid invoices -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Factures impayees
        </h2>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>N. Facture</th>
                        <th>Date</th>
                        <th style="text-align:center;">Jours retard</th>
                        <th style="text-align:right;">Montant du</th>
                        <th>Tranche</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $facture)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div class="avatar" style="width:32px;height:32px;font-size:.7rem;">
                                    {{ strtoupper(substr($facture->patient->prenom ?? '', 0, 1) . substr($facture->patient->nom ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;">{{ $facture->patient->prenom ?? '' }} {{ $facture->patient->nom ?? '' }}</div>
                                    <div class="text-muted text-sm">{{ $facture->patient->telephone ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight:600;">{{ $facture->numero }}</td>
                        <td>{{ $facture->date->format('d/m/Y') }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $facture->jours_retard }} j</td>
                        <td style="text-align:right;font-weight:700;color:#dc2626;">{{ number_format($facture->montant_restant_calc, 0, ',', ' ') }} F</td>
                        <td>
                            @switch($facture->tranche)
                                @case('0-30')
                                <span class="tranche-badge tranche-0-30">0-30j</span>
                                @break
                                @case('31-60')
                                <span class="tranche-badge tranche-31-60">31-60j</span>
                                @break
                                @case('61-90')
                                <span class="tranche-badge tranche-61-90">61-90j</span>
                                @break
                                @case('90+')
                                <span class="tranche-badge tranche-90plus">90+j</span>
                                @break
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            <div class="text-muted">Aucune creance en cours</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($factures->count() > 0)
                <tfoot>
                    <tr style="background:var(--gray-50);font-weight:700;">
                        <td colspan="4" style="text-align:right;padding:12px 16px;">TOTAL</td>
                        <td style="text-align:right;padding:12px 16px;color:#dc2626;font-size:1.1rem;">{{ number_format($totaux['total'], 0, ',', ' ') }} F</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(isset($useCharts) && $useCharts)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('agingChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['0-30 jours', '31-60 jours', '61-90 jours', '90+ jours'],
                datasets: [{
                    data: [{{ $totaux['0-30'] }}, {{ $totaux['31-60'] }}, {{ $totaux['61-90'] }}, {{ $totaux['90+'] }}],
                    backgroundColor: ['#16a34a', '#d97706', '#ea580c', '#dc2626'],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                let val = ctx.raw;
                                let total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                let pct = total > 0 ? Math.round(val / total * 100) : 0;
                                return ctx.label + ': ' + val.toLocaleString('fr-FR') + ' F (' + pct + '%)';
                            }
                        }
                    }
                },
                cutout: '60%',
            }
        });
    }
});
</script>
@endif
@endpush
