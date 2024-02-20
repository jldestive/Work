<?php

namespace App\Http\Controllers;

use App\Models\WorkUser;
use App\Http\Requests\StoreWorkUserRequest;
use App\Http\Requests\UpdateWorkUserRequest;
use App\Models\Work;
use Symfony\Component\HttpFoundation\Response;

class WorkUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(WorkUser::all()->paginate(15), Response::HTTP_OK);
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
    public function store(StoreWorkUserRequest $request)
    {
        $userId = $request->user_id;
        $work = Work::find($request->work_id);

        if($userId == $work->user_id){
            return response()->json(['message' => 'The user created this work'], Response::HTTP_BAD_REQUEST);
        }

        if($work->status != 'Closed'){
            return response()->json(['message' => 'This work is closed'], Response::HTTP_BAD_REQUEST);
        }

        $worksUser = WorkUser::where('work_id', $work->id)->where('user_id', $userId)->orderByDesc('created_at')->first();

        if($worksUser != null && $worksUser->status == 'Working'){
            return response()->json(['message' => 'The user is working in this work'], Response::HTTP_BAD_REQUEST);
        }

        $workUser = WorkUser::create([
            'work_id' => $work->id,
            'user_id' => $userId
        ]);

        $workUser->refresh();

        return response()->json($workUser, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkUser $workUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkUser $workUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkUserRequest $request, WorkUser $workUser)
    {
        $this->authorize('update', $workUser);

        $work = Work::find($workUser->work_id);

        if($work->status == 'Closed'){
            return response()->json(['message' => 'This work is closed'], Response::HTTP_BAD_REQUEST);
        }

        $workUser->update([
            'status' => $request->status
        ]);

        return response()->json($workUser, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkUser $workUser)
    {
        //
    }
}
