<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesModals;
use App\Http\Requests\Personnel\StoreTypeCongeRequest;
use App\Domain\Personnel\Models\TypeConge;

class TypeCongeController extends Controller
{
    use HandlesModals;

    public function create()
    {
        $this->authorize('create', TypeConge::class);
        return view('rh.types-conges.partials._form', ['typeConge' => new TypeConge()]);
    }

    public function store(StoreTypeCongeRequest $request)
    {
        TypeConge::create($request->validated());
        return $this->modalSuccess('Type de congé créé.');
    }

    public function edit(TypeConge $typeConge)
    {
        $this->authorize('update', $typeConge);
        return view('rh.types-conges.partials._form', compact('typeConge'));
    }

    public function update(StoreTypeCongeRequest $request, TypeConge $typeConge)
    {
        $typeConge->update($request->validated());
        return $this->modalSuccess('Type mis à jour.');
    }

    public function destroy(TypeConge $typeConge)
    {
        $this->authorize('delete', $typeConge);
        $typeConge->update(['etat' => 0]);
        return $this->modalSuccess('Type désactivé.');
    }
}