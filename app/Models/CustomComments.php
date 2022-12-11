<?php

declare(strict_types=1);

namespace App\Models;

use BeyondCode\Comments\Comment;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class CustomComments extends Comment
{
    use LogsActivity;

    protected $table = 'comments';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}