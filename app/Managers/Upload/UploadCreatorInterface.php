<?php

declare(strict_types=1);

namespace App\Managers\Upload;

use App\Models\File\File;
use Illuminate\Http\UploadedFile;

interface UploadCreatorInterface
{
    /**
     * @param UploadedFile ...$files
     * @return iterable<File>
     */
    public function create(UploadedFile ...$files): iterable;
}