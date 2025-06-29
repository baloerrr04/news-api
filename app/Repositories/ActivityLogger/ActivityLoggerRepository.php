<?php

namespace App\Repositories\ActivityLogger;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLoggerRepository implements ActivityLoggerRepositoryInterface
{
    public static function log($action, $subject = null, $description = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => is_object($subject) ? get_class($subject) : $subject,
            'subject_id' => is_object($subject) ? $subject->id : null,
            'description' => $description,
        ]);
    }
}
