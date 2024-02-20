<?php

declare(strict_types=1);

namespace App\Models\Order\Contact;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderContact extends Model implements OrderContactInterface
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'order_id',
        'type_id',
        'value',
    ];

    protected $hidden = [
        'id',
        'order_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static array $recordEvents = ['created', 'updated'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept(['created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}