<?php

declare(strict_types=1);

namespace App\Models\Board;

use App\Models\File\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Attachment extends Model
{
    protected $table = 't_task_attachments';

    protected $fillable = [
        'title',
        'link',
        'task_id',
        'file_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function file(): HasOne
    {
        return $this->hasOne(File::class);
    }
}