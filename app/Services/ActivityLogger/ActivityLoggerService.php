<?php

namespace App\Services\ActivityLogger;

use App\Repositories\ActivityLogger\ActivityLoggerRepositoryInterface;
use App\Services\ActivityLogger\ActivityLoggerServiceInterface;


class ActivityLoggerService implements ActivityLoggerServiceInterface
{

    protected $activityLogger;

    public function __construct(ActivityLoggerRepositoryInterface $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    public function logAction(string $action, $subject = null, ?string $description = null): void
    {
        $this->activityLogger->log($action, $subject, $description);
    }
}
