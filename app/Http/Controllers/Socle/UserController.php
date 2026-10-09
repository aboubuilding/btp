<?php
namespace App\Http\Controllers\Socle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Socle\{StoreUserRequest, UpdateUserRequest};
use App\Domain\Socle\Models\User;
use App\Domain\Socle\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $users) {}

    public function index(Request $request)
    {
        $users = $this->users->paginateAvecRole($request->only(['search', 'role_id', 'est_actif']));
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = $this->users->creerAvecRole(
            collect($data)->except('mot_de_passe')->toArray(),
            $data['mot_de_passe']
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur « {$user->nom} » créé.");
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['mot_de_passe'])) {
            $this->users->mettreAJourMotDePasse($user, $data['mot_de_passe']);
        }
        unset($data['mot_de_passe']);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->update(['est_actif' => false, 'etat' => 0]);
        return back()->with('success', 'Utilisateur désactivé.');
    }

    public function resetPassword(User $user)
    {
        $this->authorize('resetPassword', $user);
        $newPassword = \Str::random(12);
        $this->users->mettreAJourMotDePasse($user, $newPassword);
        return back()->with('success', "Nouveau mot de passe : {$newPassword}");
    }

    public function toggleActif(User $user)
    {
        $this->authorize('toggleActif', $user);
        $user->update(['est_actif' => !$user->est_actif]);
        return back()->with('success', 'Statut mis à jour.');
    }
}