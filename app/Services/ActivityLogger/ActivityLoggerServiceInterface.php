<?php 

namespace App\Services\ActivityLogger;

interface ActivityLoggerServiceInterface {
    public function logAction(string $action, $subject = null, ?string $description = null): void;
}