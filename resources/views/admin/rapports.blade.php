@extends('layouts.medicare')

@section('title', 'Rapports - MediCare Pro')
@section('sidebar-subtitle', 'Administration')
@section('header-title', 'Rapports mensuels')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

<!-- Hero -->
<div style="background:linear-gradient(135deg, var(--gray-800), var(--gray-700));border-radius:18px;padding:32px;margin-bottom:24px;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;opacity:.04;background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Crect x='16' y='4' width='8' height='32' rx='2' fill='%23fff'/%3E%3Crect x='4' y='16' width='32' height='8' rx='2' fill='%23fff'/%3E%3C/svg%3E\");background-size:40px 40px;pointer-events:none;"></div>
    <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
    <div style="position:relative;z-index:1;display:flex;align-items:center;gap:20px;">
        <div style="width:64px;height:64px;border-radius:18px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8M16 17H8M10 9H8"/></svg>
        </div>
        <div>
            <h2 style="font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:4px;">Rapports mensuels</h2>
            <p style="color:rgba(255,255,255,.6);font-size:.9rem;margin:0;">Générez des rapports détaillés pour le suivi de l'activité hospitalière</p>
        </div>
    </div>
</div>

<!-- Rapports disponibles -->
<div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(340px, 1fr));gap:20px;">

    <!-- Rapport mensuel -->
    <div class="card" style="overflow:hidden;">
        <div style="height:4px;background:linear-gradient(90deg, var(--primary), #22d3ee);"></div>
        <div style="padding:24px;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div style="width:48px;height:48px;border-radius:14px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:1.05rem;color:var(--gray-800);">Rapport d'activité mensuel</div>
                    <div style="font-size:.78rem;color:var(--gray-500);">Patients, consultations, recettes, dépenses, top médecins</div>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.rapport-mensuel') }}" target="_blank">
                <div style="display:flex;gap:12px;margin-bottom:16px;">
                    <div style="flex:1;">
                        <label class="form-label">Mois</label>
                        <select name="month" class="form-control">
                            @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(null, $m, 1)->locale('fr')->isoFormat('MMMM') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div style="flex:1;">
                        <label class="form-label">Année</label>
                        <select name="year" class="form-control">
                            @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;border-radius:10px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                    Générer le PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Journal de caisse -->
    <div class="card" style="overflow:hidden;">
        <div style="height:4px;background:linear-gradient(90deg, var(--success), #34d399);"></div>
        <div style="padding:24px;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div style="width:48px;height:48px;border-radius:14px;background:var(--success-light);display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:1.05rem;color:var(--gray-800);">Journal de caisse</div>
                    <div style="font-size:.78rem;color:var(--gray-500);">Entrées, sorties, solde cumulé par période</div>
                </div>
            </div>
            <form method="GET" action="{{ route('caisse.journal.pdf') }}" target="_blank">
                <div style="display:flex;gap:12px;margin-bottom:16px;">
                    <div style="flex:1;">
                        <label class="form-label">Du</label>
                        <input type="date" name="date_debut" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div style="flex:1;">
                        <label class="form-label">Au</label>
                        <input type="date" name="date_fin" class="form-control" value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-success" style="width:100%;padding:12px;border-radius:10px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                    Générer le PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Rapport journalier -->
    <div class="card" style="overflow:hidden;">
        <div style="height:4px;background:linear-gradient(90deg, var(--warning), #fbbf24);"></div>
        <div style="padding:24px;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div style="width:48px;height:48px;border-radius:14px;background:var(--warning-light);display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:1.05rem;color:var(--gray-800);">Rapport de clôture journalière</div>
                    <div style="font-size:.78rem;color:var(--gray-500);">Encaissements, dépenses, ventilation par mode de paiement</div>
                </div>
            </div>
            <form method="GET" action="{{ route('caisse.rapport-journalier') }}" target="_blank">
                <div style="margin-bottom:16px;">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}">
                </div>
                <button type="submit" class="btn" style="width:100%;padding:12px;border-radius:10px;background:var(--warning);color:#fff;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                    Générer le PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Exports CSV -->
    <div class="card" style="overflow:hidden;">
        <div style="height:4px;background:linear-gradient(90deg, var(--accent), #a78bfa);"></div>
        <div style="padding:24px;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div style="width:48px;height:48px;border-radius:14px;background:#ede9fe;display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:1.05rem;color:var(--gray-800);">Exports CSV</div>
                    <div style="font-size:.78rem;color:var(--gray-500);">Téléchargez les données au format Excel</div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <a href="{{ route('export.patients') }}" class="btn btn-outline btn-sm" style="padding:10px;border-radius:8px;text-align:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    Patients
                </a>
                <a href="{{ route('export.consultations') }}" class="btn btn-outline btn-sm" style="padding:10px;border-radius:8px;text-align:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Consultations
                </a>
                <a href="{{ route('export.medecins') }}" class="btn btn-outline btn-sm" style="padding:10px;border-radius:8px;text-align:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Médecins
                </a>
                <a href="{{ route('export.medicaments') }}" class="btn btn-outline btn-sm" style="padding:10px;border-radius:8px;text-align:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                    Médicaments
                </a>
                <a href="{{ route('export.factures') }}" class="btn btn-outline btn-sm" style="padding:10px;border-radius:8px;text-align:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                    Factures
                </a>
                <a href="{{ route('export.transactions') }}" class="btn btn-outline btn-sm" style="padding:10px;border-radius:8px;text-align:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    Transactions
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
