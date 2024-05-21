<?php

namespace App\DataTransferObject;

class IdeaFilterDto {
    public function __construct(
        public int $productId,
        public mixed $categorySlug,
        public int $tagId,
        public array $statuses,
        public string $title,
        public string $otherFilter
    ) { }

    public static function fromArray(Array $filters): IdeaFilterDto
    {
        return new self(
            productId: $filters['productId'] ?? 0,
            categorySlug: $filters['categorySlug'] ?? '',
            tagId: $filters['tagId'] ?? 0,
            statuses: $filters['statuses'] ?? [],
            title: $filters['title'],
            otherFilter: $filters['otherFilter'] ?? ''
        );
    }
}
