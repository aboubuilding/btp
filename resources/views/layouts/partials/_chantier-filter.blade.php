@php
$chantiers = $chantiersFiltre ?? \App\Domain\Execution\Models\Projet::where('etat',1)->orderBy('code')->get(['id','code','nom']);
$selected = request('projet_id');
@endphp
<div class="chantier-filter">
    <i class="fas fa-helmet-safety"></i>
    <select id="chantier-filter-select" class="chantier-filter-select">
        <option value="">Tous les chantiers</option>
        @foreach ($chantiers as $c)
            <option value="{{ $c->id }}" @selected($selected == $c->id)>{{ $c->code }} — {{ $c->nom }}</option>
        @endforeach
    </select>
</div>
<style>
.chantier-filter{display:inline-flex;align-items:center;gap:8px;padding:0 12px;height:38px;background:#fff;border:1px solid var(--btp-border);border-radius:var(--btp-radius)}
.chantier-filter i{color:var(--btp-accent);font-size:.85rem}
.chantier-filter-select{border:none;background:transparent;font-size:.83rem;font-weight:600;color:var(--btp-ink);cursor:pointer;outline:none;min-width:180px}
.dark-theme .chantier-filter{background:#161b22}
</style>
@push('js')
<script>
$(function(){
    $('#chantier-filter-select').on('change', function(){
        var url = new URL(window.location.href);
        if (this.value) url.searchParams.set('projet_id', this.value);
        else url.searchParams.delete('projet_id');
        window.location.href = url.toString();
    });
});
</script>
@endpush