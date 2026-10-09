@props(['id', 'size'=>'md'])
@php $sizeClass = ['sm'=>'modal-sm','md'=>'','lg'=>'modal-lg','xl'=>'modal-xl'][$size] ?? ''; @endphp
<div class="modal fade modal-btp" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered {{ $sizeClass }}">
        <div class="modal-content">
            <div class="modal-body-btp text-center py-5">
                <div class="spinner-border text-btp-accent"></div>
            </div>
        </div>
    </div>
</div>
<style>
.modal-btp .modal-content{border:none;border-radius:var(--btp-radius-lg);overflow:hidden;box-shadow:var(--btp-shadow-lg)}
.modal-btp .modal-header-btp{display:flex;align-items:center;justify-content:space-between;padding:18px 24px;background:linear-gradient(135deg,var(--btp-primary-dark),var(--btp-primary));color:#fff}
.modal-btp .modal-title{display:flex;align-items:center;gap:12px;font-size:1rem;font-weight:700;margin:0}
.modal-btp .modal-title i{color:var(--btp-accent)}
.btn-close-btp{width:32px;height:32px;background:rgba(255,255,255,.08);border:none;border-radius:8px;color:rgba(255,255,255,.7);cursor:pointer}
.btn-close-btp:hover{background:rgba(255,255,255,.18);color:#fff;transform:rotate(90deg)}
.modal-btp .modal-body-btp{padding:24px;max-height:70vh;overflow-y:auto}
.modal-btp .modal-footer-btp{display:flex;justify-content:flex-end;gap:10px;padding:16px 24px;background:#fafbfc;border-top:1px solid var(--btp-border-soft)}
</style>