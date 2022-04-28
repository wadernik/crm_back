<?php

namespace App\Models;

use App\Models\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use FilterableTrait;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'filename',
        'path',
        'extension',
    ];

    protected $hidden = [
        'created_at',
        'update_at',
        'deleted_at',
        'pivot',
    ];

    protected $appends = [
        'url'
    ];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url('uploads/' . $this->filename),
        );
    }
}
