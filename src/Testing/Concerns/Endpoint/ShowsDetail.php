<?php

namespace Aic\Hub\Foundation\Testing\Concerns\Endpoint;

use Illuminate\Testing\TestResponse;

trait ShowsDetail
{
    protected function getDetail($id, array $params = []): TestResponse
    {
        $uri = $this->endpoint . '/' . $id;

        if ($query = http_build_query($params)) {
            $uri .= '?' . $query;
        }

        return $this->getJson($uri);
    }

    public function test_it_shows_detail()
    {
        $item = ($this->model)::factory()->create();

        $response = $this->getDetail($item->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
            ],
        ]);
    }

    public function test_it_shows_detail_with_valid_fields()
    {
        $item = ($this->model)::factory()->create();

        $response = $this->getDetail($item->id);

        $response->assertJson(fn ($json) => $json
            ->has('data', fn ($json) => $this->validateFields($json))
            ->etc()
        );
    }

    public function test_it_shows_detail_with_valid_fields_when_nullable()
    {
        $item = ($this->model)::factory()->nullable()->create();

        $response = $this->getDetail($item->id);

        $response->assertJson(fn ($json) => $json
            ->has('data', fn ($json) => $this->validateFields($json))
            ->etc()
        );
    }

    public function test_it_400s_on_detail_if_id_is_invalid()
    {
        $id = ($this->model)::factory()->getInvalidId();

        $response = $this->getDetail($id);

        $response->assertStatus(400);
    }

    public function test_it_404s_on_detail_if_item_not_found()
    {
        $id = ($this->model)::factory()->getValidId();

        $response = $this->getDetail($id);

        $response->assertStatus(404);
    }
}
