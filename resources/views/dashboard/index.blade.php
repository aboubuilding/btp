@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page_title', 'Tableau de bord')
@section('page_icon', 'fa-gauge-high')
@section('breadcrumb')
    <li class="active">Accueil</li>
@endsection
@section('page_actions')
    @include('layouts.partials._chantier-filter')
    <x-btn variant="outline" icon="fa-file-export" size="sm">Exporter</x-btn>
@endsection

@section('contenu')
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6"><x-stat-card label="Chantiers actifs" :value="$stats['chantiers_actifs'] ?? 0" icon="fa-diagram-project" color="accent"/></div>
    <div class="col-lg-3 col-md-6"><x-stat-card label="CA facturé (mois)" :value="number_format($stats['factures_clients'] ?? 0, 0, ',', ' ').' FCFA'" icon="fa-file-invoice-dollar" color="success"/></div>
    <div class="col-lg-3 col-md-6"><x-stat-card label="Créances clients" :value="number_format($stats['impayees'] ?? 0, 0, ',', ' ').' FCFA'" icon="fa-hand-holding-dollar" color="warning"/></div>
    <div class="col-lg-3 col-md-6"><x-stat-card label="Valeur stock" :value="number_format($stats['valeur_totale'] ?? 0, 0, ',', ' ').' FCFA'" icon="fa-boxes-stacked" color="info"/></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <x-card title="Évolution du chiffre d'affaires" icon="fa-chart-line">
            <div id="chart-ca" style="height:320px"></div>
        </x-card>
    </div>
    <div class="col-lg-4">
        <x-card title="Répartition par type" icon="fa-chart-pie">
            <div id="chart-types" style="height:320px"></div>
        </x-card>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <x-card title="Chantiers en cours" icon="fa-helmet-safety">
            <x-slot:actions><a href="{{ route('projets.index') }}" class="text-decoration-none small fw-600 text-btp-accent">Voir tout <i class="fas fa-arrow-right ms-1"></i></a></x-slot:actions>
            <x-table :headers="['Code','Chantier','Client','Avancement','Budget / Réel','Statut','']" :rows="$chantiers ?? collect()">
                @foreach($chantiers ?? [] as $c)
                    <tr>
                        <td><span class="fw-700 text-btp-primary">{{ $c->code }}</span></td>
                        <td><div class="fw-600">{{ $c->nom }}</div><small class="text-btp-muted">{{ $c->ville }}</small></td>
                        <td>{{ $c->client->nom ?? '—' }}</td>
                        <td style="min-width:140px">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress progress-btp flex-grow-1"><div class="progress-bar bg-btp-accent" style="width:{{ $c->pourcentage_avancement }}%"></div></div>
                                <small class="fw-600">{{ $c->pourcentage_avancement }}%</small>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-btp-muted">Prévu : {{ number_format($c->budget_prevu, 0, ',', ' ') }}</div>
                                <div class="fw-600 {{ $c->ecart_budget >= 0 ? 'text-btp-success' : 'text-btp-danger' }}">{{ number_format($c->budget_reel, 0, ',', ' ') }} FCFA</div>
                            </div>
                        </td>
                        <td><x-badge :variant="match($c->statut){'en_cours'=>'success','planifie'=>'info','suspendu'=>'warning','termine'=>'default','annule'=>'danger',default=>'default'}">{{ ucfirst(str_replace('_',' ',$c->statut)) }}</x-badge></td>
                        <td><a href="{{ route('projets.show', $c) }}" class="btn-icon-sm"><i class="fas fa-eye"></i></a></td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    </div>
    <div class="col-lg-4">
        <x-card title="Alertes stock" icon="fa-bell">
            @forelse($alertes ?? [] as $a)
                <div style="display:flex;gap:12px;padding:12px;background:#f8f9fc;border-radius:12px;border-left:3px solid var(--btp-danger);margin-bottom:8px">
                    <div style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:#fff;border-radius:8px;color:var(--btp-danger)"><i class="fas fa-triangle-exclamation"></i></div>
                    <div>
                        <div class="fw-600">{{ $a->materiau->nom ?? '—' }}</div>
                        <small class="text-btp-muted">{{ $a->entrepot->nom ?? '' }} — Qté : {{ $a->quantite }}</small>
                    </div>
                </div>
            @empty
                <p class="text-btp-muted text-center py-4 mb-0"><i class="fas fa-check-circle text-btp-success me-2"></i>Aucune alerte</p>
            @endforelse
        </x-card>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('app/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script>
$(function(){
    if (window.ApexCharts && document.getElementById('chart-ca')) {
        new ApexCharts(document.getElementById('chart-ca'), {
            chart: { type:'area', height:320, toolbar:{show:false}, fontFamily:'Kumbh Sans' },
            series: [{ name:'CA facturé', data:[45,52,38,65,72,58,85,92,78,95,88,102] }],
            xaxis: { categories:['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'] },
            colors: ['#f0900c'],
            fill: { type:'gradient', gradient:{ shadeIntensity:1, opacityFrom:.4, opacityTo:.05 } },
            stroke: { curve:'smooth', width:3 }, dataLabels: { enabled:false }
        }).render();
    }
    if (window.ApexCharts && document.getElementById('chart-types')) {
        new ApexCharts(document.getElementById('chart-types'), {
            chart: { type:'donut', height:320, fontFamily:'Kumbh Sans' },
            series: [5,3,2,1,1],
            labels: ['Bâtiment','Route','Ouvrage d\'art','Terrassement','Réseaux'],
            colors: ['#f0900c','#1c2530','#2d8f5e','#2b6cb0','#b7950b'],
            legend: { position:'bottom' }, plotOptions: { pie:{ donut:{ size:'65%' } } }
        }).render();
    }
});
</script>
@endpush