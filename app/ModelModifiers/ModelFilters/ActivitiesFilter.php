<?php

declare(strict_types=1);

namespace App\ModelModifiers\ModelFilters;

final class ActivitiesFilter extends AbstractBaseModelFilter
{
    public function filterId(int $id): void
    {
        $this->builder->where($this->getColumnName('id'), $id);
    }

    public function filterSubject(string $subject): void
    {
        $this->builder->where($this->getColumnName('subject_type'), $subject);
    }

    public function filterSubjectId(int $subjectId): void
    {
        $this->builder->where($this->getColumnName('subject_id'), $subjectId);
    }

    /**
     * @param array<int> $subjectIds
     * @return void
     */
    public function filterSubjectIds(array $subjectIds): void
    {
        $this->builder->whereIn($this->getColumnName('subject_id'), $subjectIds);
    }

    public function filterCauserType(string $causerType): void
    {
        $this->builder->where($this->getColumnName('causer_type'), $causerType);
    }

    public function filterCauserId(int $causerId): void
    {
        $this->builder->where($this->getColumnName('causer_id'), $causerId);
    }
}