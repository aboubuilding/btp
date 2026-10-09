@extends('layouts.auth')
@section('title', 'Nouveau mot de passe')

@section('contenu')
<div class="card-btp" style="width:100%;max-width:480px;padding:40px;border-radius:20px">
    <div class="text-center mb-4">
        <div style="width:56px;height:56px;margin:0 auto 14px;display:flex;align-items:center;justify-content:center;background:var(--btp-primary);color:var(--btp-accent);border-radius:16px;font-size:1.4rem">
            <i class="fas fa-lock"></i>
        </div>
        <h3 class="fw-800 mb-1">Nouveau mot de passe</h3>
    </div>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="row g-3">
            <x-form.input name="email" label="Email" type="email" icon="fa-envelope" :required="true" :value="$email" col="col-12"/>
            <x-form.input name="password" label="Nouveau mot de passe" type="password" icon="fa-lock" :required="true" col="col-12"/>
            <x-form.input name="password_confirmation" label="Confirmation" type="password" icon="fa-lock" :required="true" col="col-12"/>
        </div>
        <div class="d-grid mt-4">
            <x-btn type="submit" variant="accent" size="lg" icon="fa-check">Réinitialiser</x-btn>
        </div>
    </form>
</div>
@endsection