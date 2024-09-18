<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Models\User;
use App\Services\UserInfoService;

class UserInfoController extends Controller
{

    protected $userInfoService;

    public function __construct(UserInfoService $userInfoService)
    {
        $this->userInfoService = $userInfoService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userInfo = UserInfo::where('user_id', session('user_id'))->first();
        $user = User::find(session('user_id'));

        if(isset($userInfo)){
            return view('userInfo.edit', compact('user', 'userInfo'));
        }else{
            return view('userInfo.index', compact('user'));
        }

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
    public function store(StoreUserInfoRequest $request)
    {
        $data = $request->validated();
        $this->userInfoService->create($data);
        return redirect()->route('userInfo.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserInfo $userInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserInfo $userInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserInfoRequest $request, UserInfo $userInfo)
    {
        $data = $request->validated();
        $this->userInfoService->update($userInfo, $data);
        return redirect()->route('userInfo.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserInfo $userInfo)
    {
        //
    }
}
