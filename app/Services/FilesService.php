<?php

namespace App\Services;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class FilesService
{
    public function uploadFile(UploadedFile $file): int
    {
        $nowTimeStamp = Carbon::now()->getTimestamp();
        $title = $this->getFileName($file->getClientOriginalName());
        // $extension = $file->getClientOriginalExtension();
        $fileName = $nowTimeStamp . '_'. $title;
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        $savedFile = File::query()
            ->create([
                'title' => $title,
                'filename' => $fileName,
                'extension' => '',
                'path' => $filePath,
            ]);

        return $savedFile['id'];
    }

    public function getFileName($name = ''): string
    {
        $nameParts = explode('.', $name);
        $extension = Arr::last($nameParts);

        return uniqid('', true) . '.' . $extension;
    }
}
