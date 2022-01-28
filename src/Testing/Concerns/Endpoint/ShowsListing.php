<?php

namespace Aic\Hub\Foundation\Testing\Concerns\Endpoint;

use Illuminate\Testing\TestResponse;

trait ShowsListing
{
    protected function getListing(array $params = []): TestResponse
    {
        $uri = $this->endpoint;

        if ($query = http_build_query($params)) {
            $uri .= '?' . $query;
        }

        return $this->getJson($uri);
    }

    private function getPageUrl(array $params = []): string
    {
        $uri = $this->endpoint;

        if ($query = http_build_query($params)) {
            $uri .= '?' . $query;
        }

        return url($uri);
    }

    public function test_it_shows_empty_listing()
    {
        $response = $this->getListing();

        $response->assertStatus(200);

        $response->assertJson([
            'pagination' => [
                'total' => 0,
                'limit' => 12,
                'offset' => 0,
                'current_page' => 1,
                'total_pages' => 1,
                'prev_url' => null,
                'next_url' => null,
            ],
        ]);

        $response->assertJson(fn ($json) => $json
            ->has('data', 0)
            ->has('info', fn ($json) => $json
                ->has('version')
                ->etc()
            )
            ->etc()
        );
    }

    public function test_it_shows_first_page_on_listing()
    {
        ($this->model)::factory()->count(12)->create();

        $response = $this->getListing([
            'limit' => 5,
            'page' => 1,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'pagination' => [
                'total' => 12,
                'limit' => 5,
                'offset' => 0,
                'current_page' => 1,
                'total_pages' => 3,
                'prev_url' => null,
                'next_url' => $this->getPageUrl([
                    'page' => 2,
                    'limit' => 5,
                ]),
            ],
        ]);

        $response->assertJson(fn ($json) => $json
            ->has('data', 5)
            ->etc()
        );
    }

    public function test_it_shows_middle_page_on_listing()
    {
        ($this->model)::factory()->count(12)->create();

        $response = $this->getListing([
            'limit' => 5,
            'page' => 2,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'pagination' => [
                'total' => 12,
                'limit' => 5,
                'offset' => 5,
                'current_page' => 2,
                'total_pages' => 3,
                'prev_url' => $this->getPageUrl([
                    'page' => 1,
                    'limit' => 5,
                ]),
                'next_url' => $this->getPageUrl([
                    'page' => 3,
                    'limit' => 5,
                ]),
            ],
        ]);

        $response->assertJson(fn ($json) => $json
            ->has('data', 5)
            ->etc()
        );
    }

    public function test_it_shows_last_page_on_listing()
    {
        ($this->model)::factory()->count(12)->create();

        $response = $this->getListing([
            'limit' => 5,
            'page' => 3,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'pagination' => [
                'total' => 12,
                'limit' => 5,
                'offset' => 10,
                'current_page' => 3,
                'total_pages' => 3,
                'prev_url' => $this->getPageUrl([
                    'page' => 2,
                    'limit' => 5,
                ]),
                'next_url' => null,
            ],
        ]);

        $response->assertJson(fn ($json) => $json
            ->has('data', 2)
            ->etc()
        );
    }

    /**
     * We allow deep pagination, but keep limit reasonable.
     */
    public function test_it_403s_if_limit_is_too_high()
    {
        $response = $this->getListing([
            'limit' => 1001,
        ]);

        $response->assertStatus(403);
    }
}
