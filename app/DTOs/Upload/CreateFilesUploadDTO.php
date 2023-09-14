<?php

declare(strict_types=1);

namespace App\DTOs\Upload;

final class CreateFilesUploadDTO implements CreateFilesUploadDTOInterface
{
    public function __construct(private readonly array $rules)
    {
    }

    public function rules(): array
    {
        return $this->rules;
    }
}