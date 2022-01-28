<?php

namespace Aic\Hub\Foundation\Testing\Concerns\Endpoint;

trait ShowsListing
{
    public function test_it_shows_empty_listing()
    {
        $response = $this->getJson($this->endpoint);

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

        $response = $this->getJson($this->endpoint . '?' . http_build_query([
            'limit' => 5,
            'page' => 1,
        ]));

        $response->assertStatus(200);

        $response->assertJson([
            'pagination' => [
                'total' => 12,
                'limit' => 5,
                'offset' => 0,
                'current_page' => 1,
                'total_pages' => 3,
                'prev_url' => null,
                'next_url' => url($this->endpoint . '?' . http_build_query([
                    'page' => 2,
                    'limit' => 5,
                ])),
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

        $response = $this->getJson($this->endpoint . '?' . http_build_query([
            'limit' => 5,
            'page' => 2,
        ]));

        $response->assertStatus(200);

        $response->assertJson([
            'pagination' => [
                'total' => 12,
                'limit' => 5,
                'offset' => 5,
                'current_page' => 2,
                'total_pages' => 3,
                'prev_url' => url($this->endpoint . '?' . http_build_query([
                    'page' => 1,
                    'limit' => 5,
                ])),
                'next_url' => url($this->endpoint . '?' . http_build_query([
                    'page' => 3,
                    'limit' => 5,
                ])),
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

        $response = $this->getJson($this->endpoint . '?' . http_build_query([
            'limit' => 5,
            'page' => 3,
        ]));

        $response->assertStatus(200);

        $response->assertJson([
            'pagination' => [
                'total' => 12,
                'limit' => 5,
                'offset' => 10,
                'current_page' => 3,
                'total_pages' => 3,
                'prev_url' => url($this->endpoint . '?' . http_build_query([
                    'page' => 2,
                    'limit' => 5,
                ])),
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
        $response = $this->getJson($this->endpoint . '?' . http_build_query([
            'limit' => 1001,
        ]));

        $response->assertStatus(403);
    }
}
