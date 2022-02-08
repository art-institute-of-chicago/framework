<?php

namespace Aic\Hub\Foundation\Console\Concerns;

use Aic\Hub\Foundation\Console\Parser;

trait HasSince
{
    protected function initHasSince()
    {
        $this->getDefinition()->addOptions([
            Parser::parseOption('since= : How far back to scan for records'),
            Parser::parseOption('full : Import records since the beginning of time'),
        ]);
    }
}
