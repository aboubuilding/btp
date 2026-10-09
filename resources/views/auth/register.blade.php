@extends('layouts.auth')
@section('title', 'Inscription')

@section('contenu')
<div class="card-btp" style="width:100%;max-width:560px;padding:40px;border-radius:20px;position:relative;overflow:hidden">
    <div style="position:absolute;top:0;left:0;right:0;height:5px;background:repeating-linear-gradient(135deg,var(--btp-accent) 0 14px,var(--btp-primary) 14px 28px)"></div>
    <div class="text-center mb-4">
        <div style="width:56px;height:56px;margin:0 auto 14px;display:flex;align-items:center;justify-content:center;background:var(--btp-primary);color:var(--btp-accent);border-radius:16px;font-size:1.4rem">
            <i class="fas fa-user-plus"></i>
        </div>
        <h3 class="fw-800 mb-1">Créer un compte</h3>
        <p class="text-btp-muted mb-0">Rejoignez BTP Manager</p>
    </div>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="row g-3">
            <x-form.input name="nom" label="Nom complet" icon="fa-user" :required="true" col="col-md-6"/>
            <x-form.input name="email" label="Email" type="email" icon="fa-envelope" :required="true" col="col-md-6"/>
            <x-form.input name="telephone" label="Téléphone" icon="fa-phone" col="col-md-6"/>
            <x-form.select name="role_demande" label="Profil souhaité" :required="true" col="col-md-6"
                :options="['conducteur_travaux'=>'Conducteur de travaux','chef_chantier'=>'Chef de chantier','magasinier'=>'Magasinier','comptable'=>'Comptable','rh'=>'RH']"/>
            <x-form.input name="mot_de_passe" label="Mot de passe" type="password" icon="fa-lock" :required="true" col="col-md-6"/>
            <x-form.input name="mot_de_passe_confirmation" label="Confirmation" type="password" icon="fa-lock" :required="true" col="col-md-6"/>
        </div>
        <div class="d-grid mt-4">
            <x-btn type="submit" variant="accent" size="lg" icon="fa-user-plus">Créer mon compte</x-btn>
        </div>
    </form>
    <p class="text-center mt-4 mb-0" style="font-size:.88rem;color:var(--btp-muted)">
        Déjà inscrit ? <a href="{{ route('login') }}" class="text-btp-accent fw-700 text-decoration-none">Se connecter</a>
    </p>
</div>
@endsection