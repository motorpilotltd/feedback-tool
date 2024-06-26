<?php

namespace App\Services\Idea;

use App\DataTransferObject\IdeaDto;
use App\Models\Idea;

class IdeaService
{
    public function store(IdeaDto $dto): Idea
    {
        return Idea::create([
            'title' => $dto->title,
            'content' => $dto->content,
            'category_id' => $dto->categoryId,
            'status' => $dto->status,
            'author_id' => $dto->authorId,
            'added_by' => $dto->addedBy,
        ]);
    }

    public function update(Idea $idea, IdeaDto $dto): Idea
    {
        return tap($idea)->update([
            'title' => $dto->title,
            'content' => $dto->content,
            'category_id' => $dto->categoryId,
            'author_id' => $dto->authorId,
        ]);
    }

    public function syncTags(Idea $idea, array $selectedTags)
    {
        // Processing saving idea Tags
        $ideaTags = [];
        collect($selectedTags)->each(function ($tags) use (&$ideaTags) {
            $ideaTags = [...$ideaTags, ...$tags];
        });
        $idea->tags()->sync($ideaTags);
    }
}
