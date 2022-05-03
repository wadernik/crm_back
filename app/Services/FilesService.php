<?php

namespace App\Services;

use App\ModelModifiers\ModelFilters\FilesFilter;
use App\Models\File;
use App\Services\Traits\FilterableTrait;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class FilesService
{
    use FilterableTrait;

    /**
     * @param array $ids
     * @param array|string[] $attributes
     * @return array
     */
    public function getFiles(array $ids, array $attributes = ['*']): array
    {
        $filesQuery = File::query();

        $filterParams = ['filter' => ['ids' => $ids]];
        $this->applyFilterParams($filesQuery, $filterParams, FilesFilter::class);

        return $filesQuery
            ->get($attributes)
            ->toArray();
    }

    /**
     * @param UploadedFile $file
     * @return array
     */
    public function uploadFile(UploadedFile $file): array
    {
        $filename = $file->hashName();
        $extension = $file->extension();
        $path = $file->storeAs('uploads', $filename, 'public');

        return File::query()
            ->create([
                'filename' => $filename,
                'path' => $path,
                'extension' => $extension,
            ])
            ->toArray();
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
