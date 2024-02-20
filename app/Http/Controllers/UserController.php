<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAvatarRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateAvatar(UpdateAvatarRequest $request){
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();
        if($user->image_path){
            Storage::disk('public')->delete($user->image_path);
        }

        if(!$request->hasFile('image')){
            $user->update([
                'avatar_path' => null,
                'user' => $user
            ]);

            return response()->json([
                'message' => 'Avatar deleted'
            ]);
        }

        $file = $request->file('image');
        $name = $file->getClientOriginalName();
        $url = null;

        $storage = Storage::disk('public')->put($name, $file);
        $url = asset('storage/' . $storage);

        $user->update([
            'avatar_path' => $url,
        ]);

        return response()->json([
            'message' => 'Avatar updated',
            'user' => $user
        ], Response::HTTP_OK);
    }
}
