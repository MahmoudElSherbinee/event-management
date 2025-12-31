<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait CanLoadRelationShips
{
    public function loadRelationships(
        Model|EloquentBuilder|QueryBuilder $for,
        ?array $relations=null
    ): Model|EloquentBuilder|QueryBuilder {
        $relations = $relations ?? $this->relations ?? [];
        $relationsToLoad = [];
        foreach($relations as $relation){
            if($this->shouldIncludeRelation($relation))
            {
                $relationsToLoad [] = $relation;
            }
        }
        if(empty($relationsToLoad))
        {
            return $for;
        }
        $result = $for instanceof Model ? $for->load($relationsToLoad) : $for->with($relationsToLoad);
        return $result;
    }

    protected function shouldIncludeRelation(string $relation) {
        $include = request()->query('include');

        if(!$include)
        {
            return false;
        }
        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }
}
