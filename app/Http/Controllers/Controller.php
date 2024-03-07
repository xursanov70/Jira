<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function can(string $module, $action)
    {
        $check = UserRole::select('*')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->join('modules', 'roles.module_id', '=', 'modules.id')
            ->join('actions', 'roles.action_id', '=', 'actions.id')
            ->where('modules.module_name', $module)
            ->where('actions.action_name', $action)
            ->where('user_roles.user_id', Auth::user()->id)
            ->count();

        if ($check > 0) {
            return 'can';
        } else {
            return 'denied';
        }
    }
}
