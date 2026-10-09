<?php
namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;

trait HandlesModals
{
    protected function modalSuccess(string $message, ?string $redirect = null, array $extra = []): JsonResponse
    {
        return response()->json(array_merge([
            'success'  => true,
            'message'  => $message,
            'redirect' => $redirect,
        ], $extra));
    }

    protected function modalError(string $message, array $errors = [], int $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    /** Retourne une vue partielle si requête XHR, sinon la vue complète. */
    protected function viewOrPartial(string $partial, string $full, array $data = [])
    {
        return request()->ajax() ? view($partial, $data) : view($full, $data);
    }
}