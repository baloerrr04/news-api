<?php 

namespace App\Repositories\ActivityLogger;

use App\Models\ActivityLog;

interface ActivityLoggerRepositoryInterface {
   public static function log($action, $subject = null, $description = null);
}