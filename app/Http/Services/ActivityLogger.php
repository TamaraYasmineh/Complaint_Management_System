<?php
namespace App\Http\Services;

use App\Models\Activity_log;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $model = null, $before = null, $after = null)
    {
        Activity_log::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'before' => $before ? json_encode($before, JSON_UNESCAPED_UNICODE) : null,
            'after' => $after ? json_encode($after, JSON_UNESCAPED_UNICODE) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
