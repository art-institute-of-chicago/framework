<?php

namespace Aic\Hub\Foundation\Library\Api\Builders\Grammar;

class SearchGrammar extends AicGrammar
{
    protected function compileBoost($query, $boost)
    {
        return [
            'boost' => $boost,
        ];
    }
}
