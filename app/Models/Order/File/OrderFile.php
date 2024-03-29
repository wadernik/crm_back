<?php

declare(strict_types=1);

namespace App\Models\Order\File;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class OrderFile extends Pivot
{
    use LogsActivity;

    public $incrementing = true;

    protected $table = 'order_files';

    protected $fillable = [
        'order_item_id',
        'file_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}