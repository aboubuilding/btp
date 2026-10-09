<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Domain\Socle\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __construct(private UserRepositoryInterface $users) {}

    public function show()
    {
        return view('auth.forgot-password');
    }

    public function send(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Un lien de réinitialisation vous a été envoyé.')
            : back()->withErrors(['email' => __($status)]);
    }
}