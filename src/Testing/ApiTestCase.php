<?php

namespace Aic\Hub\Foundation\Testing;

use LogicException;
use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class ApiTestCase extends BaseTestCase
{
    /**
     * It is reasonable to expect that this trait must exist. See docs.
     *
     * @link https://laravel.com/docs/8.x/testing#the-creates-application-trait
     */
    use CreatesApplication;

    /**
     * @link https://laravel.com/docs/8.x/database-testing#resetting-the-database-after-each-test
     */
    use RefreshDatabase;

    /**
     * Required. Ex: 'app/v1/artworks'
     */
    protected $endpoint;

    /**
     * Required. Ex: \App\Models\Artworks::class
     */
    protected $model;

    /**
     * Work-around for "Properties cannot be declared abstract" error.
     */
    protected function setUp(): void
    {
        foreach (['endpoint', 'model'] as $property) {
            if (!isset($this->{$property})) {
                throw new LogicException(get_class($this) . ' missing $' . $property);
            }
        }

        parent::setUp();
    }

    public function test_it_shows_detail()
    {
        $item = ($this->model)::factory()->create();

        $response = $this->getJson($this->endpoint . '/' . $item->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
            ],
        ]);
    }

    public function test_it_400s_on_detail_if_id_is_invalid()
    {
        $id = ($this->model)::factory()->getInvalidId();

        $response = $this->getJson($this->endpoint . '/' . $id);

        $response->assertStatus(400);
    }

    public function test_it_404s_on_detail_if_item_not_found()
    {
        $id = ($this->model)::factory()->getValidId();

        $response = $this->getJson($this->endpoint . '/' . $id);

        $response->assertStatus(404);
    }

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
        ($this->model)::factory()->count(7)->create();

        $response = $this->getJson($this->endpoint . '?' . http_build_query([
            'limit' => 5,
        ]));

        $response->assertStatus(200);

        $response->assertJson([
            'pagination' => [
                'total' => 7,
                'limit' => 5,
                'offset' => 0,
                'current_page' => 1,
                'total_pages' => 2,
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
}
