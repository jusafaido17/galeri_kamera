<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MemberController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */
        $user = Auth::user();
        $memberInfo = $user->getMemberLevelInfo();
        $progress = $user->getProgressToNextLevel();

        return view('member.dashboard', compact('memberInfo', 'progress'));
    }
}
