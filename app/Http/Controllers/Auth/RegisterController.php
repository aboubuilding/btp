<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Domain\Socle\Repositories\{UserRepositoryInterface, RoleRepositoryInterface};
use App\Domain\Socle\Events\UtilisateurCree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class RegisterController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $users,
        private RoleRepositoryInterface $roles,
    ) {}

    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nom'          => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'telephone'    => ['nullable', 'string', 'max:30'],
            'role_demande' => ['required', 'exists:roles,slug'],
            'mot_de_passe' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        return DB::transaction(function () use ($data) {
            $role = $this->roles->findBySlug($data['role_demande']);

            $user = $this->users->creerAvecRole([
                'nom'       => $data['nom'],
                'email'     => $data['email'],
                'telephone' => $data['telephone'] ?? null,
                'role_id'   => $role->id,
                'est_actif' => false, // Activation par admin
            ], $data['mot_de_passe']);

            event(new UtilisateurCree($user));

            return redirect()->route('login')
                ->with('success', 'Compte créé. En attente d\'activation par un administrateur.');
        });
    }
}