<?php

namespace App\Services;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class FilesService
{
    /**
     * @param UploadedFile $file
     * @return int file id
     */
    public function uploadFile(UploadedFile $file): int
    {
        $nowTimeStamp = Carbon::now()->getTimestamp();
        $title = $this->generateFilename($file->getClientOriginalName());
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

    /**
     * @param string $name
     * @return string
     */
    private function generateFilename(string $name = ''): string
    {
        $nameParts = explode('.', $name);
        $extension = Arr::last($nameParts);

        return uniqid('', true) . '.' . $extension;
    }
}
