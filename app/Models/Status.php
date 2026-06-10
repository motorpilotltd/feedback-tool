<?php

namespace App\Models;

use App\Traits\AvoidDuplicateConstraintSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Unguarded]
class Status extends Model
{
    use AvoidDuplicateConstraintSoftDelete, HasFactory, SoftDeletes;

    /**
     * Columns with a unique constraint that must be freed when the row is
     * soft-deleted, so the slug can be reused (e.g. re-seeded) afterwards.
     */
    public function getDuplicateAvoidColumns(): array
    {
        return ['slug'];
    }
}
