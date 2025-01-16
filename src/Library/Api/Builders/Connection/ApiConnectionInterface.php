<?php

namespace Aic\Hub\Foundation\Library\Api\Builders\Connection;

interface ApiConnectionInterface
{
    public function __construct();
    public function get($endpoint, $params);
    public function execute($endpoint = null, $params = []);
}
