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
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model?->id,
            'before' => $before,
            'after' => $after,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
