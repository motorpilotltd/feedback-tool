<?php

namespace App\Filters\Common;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class SearchField extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        $payload = $this->builderState;
        $keyword = trim($payload['value']);
        if (empty($keyword)) {
            return $builder;
        }
        $field = $payload['field'];
        // Concatenate multiple fields into a single string

        $fieldStr = is_array($field) ? implode('_', $field).'_text' : $field;

        if (strpos($keyword, ' ')) {
            // split on 1+ whitespace & ignore empty (eg. trailing space)
            $keywordArr = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
            $builderRaw = $builder->where(function ($query) use ($keywordArr, $field, $keyword) {
                foreach ($keywordArr as $keyword) {
                    if (! is_array($field)) {
                        $query->orWhere($field, 'like', "%{$keyword}%");
                    } else {
                        foreach ($field as $fld) {
                            $query->orWhere($fld, 'like', "%{$keyword}%");
                        }
                    }

                }
            });
        } else {
            if (! is_array($field)) {
                $builderRaw = $builder->where($field, 'like', '%'.$keyword.'%');
            } else {
                $builderRaw = $builder->where(function ($query) use ($field, $keyword) {
                    foreach ($field as $fld) {
                        $query->orWhere($fld, 'like', '%'.$keyword.'%');
                    }
                });
            }
        }

        return $this->orderByKeywordRaw($builderRaw, $fieldStr, $keyword);
    }

    protected function orderByKeywordRaw($builder, $fieldStr, $keyword)
    {
        if (app()->environment('testing')) {
            return $builder;
        }

        return $builder->orderByRaw("MATCH($fieldStr) AGAINST(? IN BOOLEAN MODE) DESC", [$keyword]);
    }
}
