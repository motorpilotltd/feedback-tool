<?php

namespace App\DataTransferObject;

class IdeaDto {
    public function __construct(
        public string $title,
        public string $content,
        public int $categoryId,
        public string $status,
        public ?int $authorId,
        public int $addedBy
    ) {}

    public static function fromArray(array $data) {
        return new self(
            title: $data['title'],
            content: $data['content'],
            categoryId: $data['category'],
            status: $data['status'],
            authorId: $data['authorId'],
            addedBy: $data['addedBy']
        );
    }
}
