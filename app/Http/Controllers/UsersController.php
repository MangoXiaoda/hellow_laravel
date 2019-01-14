<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Auth;

class UsersController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index']
        ]);

        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }


    // 用户列表
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }


    //显示用户个人信息的页面
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }


    // 用户数据验证
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|max:50',
            'email'    => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程!!');
        return redirect()->route('users.show', [$user]);
    }


    // 修改
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 更新
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success', '个人资料更新成功!');

        return redirect()->route('users.show', $user->id);
    }

    // 删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);

        $user->delete();

        session()->flash('success', '成功删除用户！');

        return back();
    }
}