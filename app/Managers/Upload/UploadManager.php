<?php

declare(strict_types=1);

namespace App\Managers\Upload;

use App\Models\File\File;
use Illuminate\Http\UploadedFile;

final class UploadManager implements UploadManagerInterface
{
    public function create(UploadedFile ...$files): iterable
    {
        foreach ($files as $file) {
            $filename = $file->hashName();
            $extension = $file->extension();
            $path = $file->storeAs('uploads', $filename, 'public');

            yield File::query()
                ->create([
                    'filename' => $filename,
                    'path' => $path,
                    'extension' => $extension,
                ]);
        }
    }
}