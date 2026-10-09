@extends('layouts.auth')
@section('title', 'Mot de passe oublié')

@section('contenu')
<div class="card-btp" style="width:100%;max-width:480px;padding:40px;border-radius:20px">
    <div class="text-center mb-4">
        <div style="width:56px;height:56px;margin:0 auto 14px;display:flex;align-items:center;justify-content:center;background:var(--btp-primary);color:var(--btp-accent);border-radius:16px;font-size:1.4rem">
            <i class="fas fa-key"></i>
        </div>
        <h3 class="fw-800 mb-1">Mot de passe oublié</h3>
        <p class="text-btp-muted mb-0">Saisissez votre email pour recevoir un lien</p>
    </div>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <x-form.input name="email" label="Adresse email" type="email" icon="fa-envelope" :required="true"/>
        <div class="d-grid mt-4">
            <x-btn type="submit" variant="accent" size="lg" icon="fa-paper-plane">Envoyer le lien</x-btn>
        </div>
    </form>
    <p class="text-center mt-4 mb-0" style="font-size:.88rem;color:var(--btp-muted)">
        <a href="{{ route('login') }}" class="text-btp-accent fw-700 text-decoration-none"><i class="fas fa-arrow-left me-1"></i>Retour</a>
    </p>
</div>
@endsection