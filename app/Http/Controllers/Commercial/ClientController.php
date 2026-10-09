<?php
namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\{StoreClientRequest, UpdateClientRequest};
use App\Domain\Commercial\Models\Client;
use App\Domain\Commercial\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(private ClientService $service) {}

    public function index(Request $request)
    {
        $clients = $this->service->paginate($request->only(['search', 'type']));
        return view('commercial.clients.index', compact('clients'));
    }

    public function create()
    {
        $this->authorize('create', Client::class);
        return view('commercial.clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        $client = $this->service->creer($request->validated());
        return redirect()->route('commercial.clients.show', $client)->with('success', 'Client créé.');
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);
        $client = $this->service->avecHistorique($client);
        return view('commercial.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $this->authorize('update', $client);
        return view('commercial.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $this->service->mettreAJour($client, $request->validated());
        return redirect()->route('commercial.clients.show', $client)->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);
        $this->service->desactiver($client);
        return redirect()->route('commercial.clients.index')->with('success', 'Client désactivé.');
    }
}