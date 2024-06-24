<?php

namespace App\DataTransferObject;

class UserFilterDto
{
    public function __construct(
        public mixed $role,
        public mixed $permission,
        public array $searchFields,
        public string $searchValue
    ) {
    }

    public static function fromArray(array $filters): UserFilterDto
    {
        return new self(
            role: $filters['role'] ?? [],
            permission: $filters['permission'] ?? [],
            searchFields: $filters['searchFields'] ?? ['name'],
            searchValue: $filters['searchValue'] ?? '',
        );
    }
}
