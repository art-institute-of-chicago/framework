<?php

namespace Aic\Hub\Foundation\Library\Api\Builders\Relations;

use A17\Twill\Models\Contracts\TwillModelContract;
use Aic\Hub\Foundation\Library\Api\Models\Behaviors\HasApiModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BaseBelongsTo;

class BelongsTo extends BaseBelongsTo
{
    public function __construct(Builder $query, TwillModelContract $child, $foreignKey, $ownerKey, $relationName)
    {
        $this->ownerKey = $ownerKey;
        $this->relationName = $relationName;
        $this->foreignKey = $foreignKey;
        $this->child = $child;
        $this->query = $query;
        $this->parent = $child;
        $this->related = $query->getModel();
        $this->addConstraints();
    }

    public function getResults()
    {
        if (in_array(HasApiModel::class, class_uses_recursive($this->child::class))) {
            $this->child->refreshApi();
            $id = $this->child->getApiModelFilled()->{$this->foreignKey};
        } else {
            $id = $this->child->getAttribute($this->foreignKey);
        }
        if ($id) {
            return $this->query->find($id);
        }
        return null;
    }
}
