<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PhonenumberRequest;
use App\Phonenumber;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PhonenumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === "admin") {
            $phonenumbers = Phonenumber::paginate(10);
            return response()->json([
                'phonenumbers' => $phonenumbers
            ]);
        }

        $phonenumbers = Phonenumber::where('user_id', $user->id)->paginate(10);
        return response()->json([
            'phonenumbers' => $phonenumbers
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
     * @param PhonenumberRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PhonenumberRequest $request)
    {
        $this->authorize('create', Phonenumber::class);

        $phonenumber = new Phonenumber;
        $phonenumber->value = $request->phonenumber;
        $phonenumber->user_id = $request->user()->id;
        $phonenumber->save();

        return response()->json([
            'message' => "New phonenumber successfully added!",
            'phonenumber' => $phonenumber
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $phonenumber = Phonenumber::find($id);

        $this->authorize('view', $phonenumber);
        return response()->json([
            'phonenumber' => $phonenumber
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
            'message' => 'Not found!'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'phonenumber' => 'required|phone:AUTO,PH|unique:phonenumbers,value,' . $id,
        ]);

        $phonenumber = Phonenumber::find($id);

        $this->authorize('update', $phonenumber);

        return response()->json([
            'message' => "Phonenumber has been updated!",
            'phonenumber' => $phonenumber
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $phonenumber = Phonenumber::find($id);

        $this->authorize('delete', $phonenumber);
        Phonenumber::destroy($id);
        return response()->json([
            'message' => "Phone number deleted!"
        ]);
    }
}
