@extends('layouts.auth')
@section('title', 'Connexion')

@section('contenu')
<div style="width:100%;max-width:1000px;display:flex;background:#fff;border-radius:20px;box-shadow:0 25px 60px rgba(10,15,22,.16);overflow:hidden;min-height:600px">

    {{-- Panel gauche --}}
    <aside style="flex:0 0 44%;background:linear-gradient(160deg,var(--btp-primary-dark) 0%,var(--btp-primary) 40%,var(--btp-secondary) 100%);padding:48px 44px;color:#fff;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden">
        <div style="position:absolute;top:0;left:0;right:0;height:5px;background:repeating-linear-gradient(135deg,var(--btp-accent) 0 14px,var(--btp-primary) 14px 28px)"></div>
        <div style="position:relative;z-index:2">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:36px">
                <div style="width:60px;height:60px;background:rgba(240,144,12,.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.9rem;color:var(--btp-accent);border:1px solid rgba(240,144,12,.28)">
                    <i class="fas fa-helmet-safety"></i>
                </div>
                <div>
                    <div style="font-weight:800;font-size:1.5rem;line-height:1.2">BTP Manager</div>
                    <small style="font-weight:500;font-size:.6rem;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.5);display:block;margin-top:4px">Gestion intégrée des chantiers</small>
                </div>
            </div>
            <span style="display:inline-flex;align-items:center;gap:10px;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:2.5px;color:var(--btp-accent-light);margin-bottom:14px">
                <span style="width:28px;height:2px;background:var(--btp-accent)"></span>Plateforme professionnelle
            </span>
            <h1 style="font-weight:800;font-size:2.4rem;line-height:1.15;margin-bottom:14px;letter-spacing:-.5px">Gérez vos chantiers en <span style="color:var(--btp-accent)">toute efficacité</span></h1>
            <p style="font-size:.88rem;color:rgba(255,255,255,.75);line-height:1.7;margin-bottom:28px">
                Solution complète : projets, équipes, engins, stocks, finances et reporting, du bureau au chantier.
            </p>
        </div>
    </aside>

    {{-- Panel droit --}}
    <div style="flex:1;padding:48px 56px;display:flex;flex-direction:column;justify-content:center">
        <div style="max-width:380px;margin:0 auto;width:100%">
            <div style="margin-bottom:28px">
                <div style="width:56px;height:56px;background:linear-gradient(135deg,var(--btp-primary),var(--btp-secondary));border-radius:16px;display:inline-flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--btp-accent-light);margin-bottom:14px">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h3 style="font-weight:800;font-size:1.5rem;color:var(--btp-ink);margin-bottom:4px">Connexion</h3>
                <p style="color:var(--btp-muted);font-size:.88rem;margin:0">Accédez à votre espace de travail</p>
            </div>

            <div id="alert-banner" style="display:none;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:.85rem"></div>

            <form id="form-login" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label-btp"><i class="fas fa-envelope"></i> Email *</label>
                    <div class="input-btp-group">
                        <span class="input-btp-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control-btp" placeholder="exemple@domaine.com" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-btp"><i class="fas fa-lock"></i> Mot de passe *</label>
                    <div class="input-btp-group">
                        <span class="input-btp-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control-btp" placeholder="••••••••" required>
                        <span class="input-btp-icon" id="togglePassword" style="cursor:pointer"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4" style="font-size:.85rem">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" name="remember"> Se souvenir de moi
                    </label>
                    <a href="{{ route('password.request') }}" style="color:var(--btp-primary);font-weight:600;text-decoration:none">Mot de passe oublié ?</a>
                </div>
                <button type="submit" id="btn-login" style="width:100%;border:none;border-radius:12px;padding:14px;font-weight:700;font-size:.95rem;color:#fff;background:linear-gradient(120deg,var(--btp-primary),var(--btp-secondary));cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>

            <p style="text-align:center;margin-top:20px;font-size:.88rem;color:var(--btp-muted)">
                Nouveau ? <a href="{{ route('register') }}" style="color:var(--btp-accent);font-weight:700;text-decoration:none">Créer un compte</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(function(){
    $('#togglePassword').on('click', function(){
        var $input = $('#mot_de_passe'), $icon = $(this).find('i');
        var show = $input.attr('type') === 'password';
        $input.attr('type', show ? 'text' : 'password');
        $icon.toggleClass('fa-eye-slash', show).toggleClass('fa-eye', !show);
    });

    $('#form-login').on('submit', function(e){
        e.preventDefault();
        var email = $('#email').val().trim();
        var pwd = $('#mot_de_passe').val();
        if (!email || !pwd) { toastr.warning('Email et mot de passe obligatoires.'); return; }

        var $btn = $('#btn-login');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Connexion…');

        $.ajax({
            url: '{{ route("login.post") }}',
            method: 'POST',
            data: { email: email, mot_de_passe: pwd, _token: '{{ csrf_token() }}' },
            dataType: 'json'
        }).done(function(data){
            if (data.success) {
                toastr.success(data.message || 'Connexion réussie');
                setTimeout(function(){ window.location.href = data.redirect || '{{ route("dashboard") }}'; }, 700);
            } else {
                toastr.error(data.message || 'Erreur');
                $btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt"></i> Se connecter');
            }
        }).fail(function(xhr){
            var data = xhr.responseJSON || {};
            toastr.error(data.message || 'Email ou mot de passe incorrect.');
            $btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt"></i> Se connecter');
        });
    });
});
</script>
@endpush