<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $clients = Client::where('user_id', $user->id)->paginate(10);
        return response()->json([
            'clients' => $clients
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'not found!'
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClientRequest $request
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function store(ClientRequest $request)
    {
        if ($request->user()->cant('create', Client::class)) {
            throw new AuthorizationException;
        }

        $client = new Client;
        $client->name = $request->name;
        $client->user_id = $request->user()->id;
        $client->save();

        return response()->json([
            'client' => $client
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function show($id)
    {
        $client = Client::find($id);
        $this->authorize('view', $client);

        return response()->json([
            'client' => $client
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json([
            'not found!'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function update(ClientRequest $request, $id)
    {
        $client = Client::find($id);

        $this->authorize('update', $client);
        $client->name = $request->name;
        $client->save();

        return response()->json([
            'message' => 'Client has been updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function destroy($id)
    {
        $client = Client::find($id);

        $this->authorize('delete', $client);
        $client->delete();

        return response()->json([
            'message' => 'Client deleted!'
        ]);
    }
}
