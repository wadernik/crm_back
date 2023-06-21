<?php

declare(strict_types=1);

namespace App\Models\Activity;

/**
 * @property string $subject_type
 */
interface ActivityInterface
{
    /**
     * @return array<array{
     *     id: int,
     *     name: string,
     *     display_name: string
     * }>
     */
    public static function getSubjectsList(): array;
}