@extends('layouts.medicare')

@section('title', 'Journal d\'audit - MediCare Pro')
@section('sidebar-subtitle', 'Gestion Hospitalière')
@section('header-title', 'Journal d\'audit')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

<div class="toolbar">
    <div style="display:flex;gap:10px;flex:1;align-items:center;">
        <input type="text" id="auditSearch" class="filter-input" placeholder="Rechercher dans les descriptions...">
        <form method="GET" action="{{ route('admin.audit-log') }}" style="display:flex;gap:10px;align-items:center;">
            <select name="action" class="filter-select" onchange="this.form.submit()">
                <option value="">Toutes les actions</option>
                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Création</option>
                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Modification</option>
                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Suppression</option>
            </select>
            @if(request('action'))
            <a href="{{ route('admin.audit-log') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
            @endif
        </form>
        <span id="auditCount" style="font-size:.82rem;color:var(--gray-500);white-space:nowrap;"></span>
    </div>
</div>

@if($logs->count() > 0)
<table class="table-patients">
    <thead>
        <tr>
            <th>Date</th>
            <th>Utilisateur</th>
            <th>Action</th>
            <th>Description</th>
            <th>IP</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $log->user ? $log->user->name : 'Système' }}</td>
            <td>
                @if($log->action === 'create')
                    <span class="badge badge-success">Création</span>
                @elseif($log->action === 'update')
                    <span class="badge badge-info">Modification</span>
                @elseif($log->action === 'delete')
                    <span class="badge badge-danger">Suppression</span>
                @else
                    <span class="badge">{{ $log->action }}</span>
                @endif
            </td>
            <td>{{ $log->description }}</td>
            <td>{{ $log->ip_address }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 16px;">
    {{ $logs->withQueryString()->links() }}
</div>
@else
<div style="text-align:center;padding:60px 20px;color:var(--gray-500);">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:12px;opacity:0.5;">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
    </svg>
    <p style="font-size:16px;font-weight:500;">Aucune entrée dans le journal d'audit</p>
    <p style="font-size:14px;">Les actions critiques seront enregistrées ici.</p>
</div>
@endif

@push('scripts')
<script>
(function() {
    var searchInput = document.getElementById('auditSearch');
    var rows = document.querySelectorAll('.table-patients tbody tr');
    var countEl = document.getElementById('auditCount');

    function filterRows() {
        var q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var visible = 0;
        var total = 0;

        rows.forEach(function(row) {
            if (row.querySelector('td[colspan]')) return;
            total++;
            var text = row.textContent.toLowerCase();
            var matchSearch = !q || text.includes(q);
            if (matchSearch) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        if (countEl) countEl.textContent = visible + ' / ' + total + ' résultats';
    }

    if (searchInput) searchInput.addEventListener('input', filterRows);
    filterRows();
})();
</script>
@endpush

@endsection
