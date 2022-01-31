<?php

namespace Aic\Hub\Foundation\Testing\Concerns;

use Aic\Hub\Foundation\Concerns\HasAbstractProperties;

trait HasEndpoint
{
    use HasAbstractProperties;
    use Endpoint\ShowsDetail;
    use Endpoint\ShowsListing;
    use Endpoint\ShowsMultiple;
    use Endpoint\HasFieldsParam;

    /**
     * Required. Ex: 'app/v1/artworks'
     */
    protected $endpoint;

    protected function setUpHasEndpoint()
    {
        $this->checkProperty('endpoint');
    }
}
