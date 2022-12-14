<?php

declare(strict_types=1);

namespace App\Models;

use BeyondCode\Comments\Comment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class CustomComments extends Comment
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'comments';

    protected $hidden = [
        'deleted_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}