@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('page_title', 'Utilisateurs')
@section('page_icon', 'fa-users-cog')
@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Accueil</a></li>
    <li>Admin</li>
    <li class="active">Utilisateurs</li>
@endsection
@section('page_actions')
    <x-btn href="{{ route('admin.users.create') }}" variant="accent" icon="fa-user-plus" size="sm">Nouvel utilisateur</x-btn>
@endsection

@section('contenu')
<x-table :headers="['Nom','Email','Rôle','Statut','Dernière connexion','Actions']" :rows="$users">
    @foreach($users as $u)
        <tr>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,var(--btp-accent-light),var(--btp-accent));color:#fff;font-size:.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border-radius:50%">{{ strtoupper(substr($u->nom,0,2)) }}</div>
                    <span class="fw-600">{{ $u->nom }}</span>
                </div>
            </td>
            <td>{{ $u->email }}</td>
            <td><x-badge variant="accent">{{ $u->role?->nom ?? '—' }}</x-badge></td>
            <td>@if($u->est_actif)<x-badge variant="success" dot>Actif</x-badge>@else<x-badge variant="danger" dot>Inactif</x-badge>@endif</td>
            <td><small class="text-btp-muted">{{ $u->derniere_connexion_le?->diffForHumans() ?? 'Jamais' }}</small></td>
            <td><a href="{{ route('admin.users.edit', $u) }}" class="btn-icon-sm"><i class="fas fa-pen"></i></a></td>
        </tr>
    @endforeach
</x-table>
<div class="mt-3">{{ $users->links() }}</div>
@endsection