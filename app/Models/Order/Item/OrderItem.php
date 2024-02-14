<?php

namespace App\Models\Order\Item;

use App\Models\File\File;
use App\Models\Order\File\OrderFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderItem extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'title_id',
        'unit_id',
        'name',
        'amount',
        'label',
        'comment',
        'decoration',
        'decoration_type',
    ];

    protected $hidden = [
        'order_id',
        'name',
        'deleted_at',
    ];

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'order_files', 'order_item_id', 'file_id')->using(OrderFile::class);
    }

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