<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserChangeRequest;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    // 一覧
    public function index()
    {
        $user_request = UserChangeRequest::with(['targetUser', 'requester'])->get();

        return response()->view('/admin/users/approve', compact('user_request'));
    }

    // 承認操作画面表示
    public function show()
    {

    }

    // 承認ロジック
    public function approve()
    {

    }

    // 棄却ロジック
    public function reject()
    {

    }
}
