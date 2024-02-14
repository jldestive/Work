<?php

namespace App\Http\Controllers;

use App\Models\RequestUser;
use App\Http\Requests\StoreRequestUserRequest;
use App\Http\Requests\UpdateRequestUserRequest;
use App\Models\Work;
use App\Models\WorkUser;
use Symfony\Component\HttpFoundation\Response;

class RequestUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(RequestUser::all()->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestUserRequest $request)
    {
        /*** @var App/Models/User*/
        $user = auth()->user();

        $work = Work::find($request->work_id);

        if($user->id == $work->user_id){
            return response()->json(['message' => 'The same user can not requets this work'], Response::HTTP_BAD_REQUEST);
        }

        if($work->status == 'Closed'){
            return response()->json(['message' => 'This work is closed'], Response::HTTP_BAD_REQUEST);
        }

        $workUser = WorkUser::where('user_id', $user->id)->where('work_id', $work->id)->orderByDesc('updated_at')->first();

        if($workUser != null && $workUser->status == 'Working'){
            return response()->json(['message' => 'The user is working in this work'], Response::HTTP_BAD_REQUEST);
        }

        $requestUser = RequestUser::create([
            'work_id' => $work->id,
            'user_id' => $user->id,
        ]);

        $requestUser->refresh();

        return response()->json($requestUser, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestUser $requestUser)
    {
        if($requestUser == null){
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()->json($requestUser, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestUser $requestUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequestUserRequest $request, RequestUser $requestUser)
    {
        $this->authorize('update', $requestUser);
        
        $work = Work::find($requestUser->work_id);
        if($work->status == 'Closed'){
            return response()->json(['message' => 'This work is closed'], Response::HTTP_BAD_REQUEST);
        }
        $requestUser->update([
            'status' => $request->status
        ]);

        return response()->json($requestUser, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestUser $requestUser)
    {
        $requestUser->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
