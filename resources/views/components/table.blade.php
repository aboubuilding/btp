@props(['headers'=>[], 'rows'=>null, 'empty'=>'Aucun enregistrement', 'emptyIcon'=>'fa-inbox'])
<div class="table-responsive table-btp-wrapper">
    <table class="table-btp">
        <thead><tr>@foreach($headers as $h)<th>{!! $h !!}</th>@endforeach</tr></thead>
        <tbody>
            @if($rows && $rows->count() > 0)
                {{ $slot }}
            @else
                <tr><td colspan="{{ count($headers) }}" class="table-empty text-center py-5">
                    <i class="fas {{ $emptyIcon }} text-btp-muted" style="font-size:3rem"></i>
                    <p class="mt-2 text-btp-muted mb-0">{{ $empty }}</p>
                </td></tr>
            @endif
        </tbody>
    </table>
</div>
<style>
.table-btp-wrapper{background:var(--btp-card-bg);border:1px solid var(--btp-border);border-radius:var(--btp-radius-lg);overflow:hidden}
.table-btp{width:100%;margin:0;border-collapse:separate;border-spacing:0}
.table-btp thead{background:#f8f9fc;border-bottom:2px solid var(--btp-border)}
.table-btp th{padding:14px 18px;font-size:.72rem;font-weight:800;text-transform:uppercase;color:var(--btp-muted);text-align:left;white-space:nowrap}
.table-btp td{padding:14px 18px;font-size:.88rem;color:var(--btp-ink);border-bottom:1px solid var(--btp-border-soft);vertical-align:middle}
.table-btp tbody tr:hover{background:#fafbfc}
.table-btp tbody tr:last-child td{border-bottom:none}
.dark-theme .table-btp thead{background:#0d1117}
.dark-theme .table-btp tbody tr:hover{background:#1c2333}
</style>