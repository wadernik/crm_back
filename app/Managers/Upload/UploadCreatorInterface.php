<?php

declare(strict_types=1);

namespace App\Managers\Upload;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface UploadCreatorInterface
{
    /**
     * @param UploadedFile ...$files
     * @return iterable<Model>
     */
    public function create(UploadedFile ...$files): iterable;
}