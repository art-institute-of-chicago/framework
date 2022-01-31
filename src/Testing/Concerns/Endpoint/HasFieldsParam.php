<?php

namespace Aic\Hub\Foundation\Testing\Concerns\Endpoint;

trait HasFieldsParam
{
    /**
     * The `fields` param could be an array or a comma-separated string.
     *
     * @link https://github.com/laravel/framework/issues/19871
     */
    private function it_shows_detail_with_fields(callable $getFieldsParam)
    {
        $item = ($this->model)::factory()->create();

        $selectedFields = array_keys(array_slice($this->fields(), 0, 2));

        $response = $this->getDetail($item->id, [
            'fields' => $getFieldsParam($selectedFields),
        ]);

        $response->assertStatus(200);

        $response->assertJson(fn ($json) => $json
            ->has('data', fn ($json) => $json
                ->hasAll(...$selectedFields)
            )
            ->etc()
        );
    }

    private function it_shows_listing_with_fields(callable $getFieldsParam)
    {
        ($this->model)::factory()->count(3)->create();

        $selectedFields = array_keys(array_slice($this->fields(), 0, 2));

        $response = $this->getListing([
            'fields' => $getFieldsParam($selectedFields),
        ]);

        $response->assertStatus(200);

        $response->assertJson(fn ($json) => $json
            ->has('data', fn ($json) => $json
                ->each(fn ($json) => $json
                    ->hasAll(...$selectedFields)
                )
            )
            ->etc()
        );
    }

    private function it_shows_multiple_with_fields(callable $getFieldsParam)
    {
        $items = ($this->model)::factory()->count(3)->create()->shuffle();

        $selectedFields = array_keys(array_slice($this->fields(), 0, 2));

        $key = $items->first()->getKeyName();
        $shownIds = $items->slice(0, 2)->pluck($key)->all();

        $response = $this->getListing([
            'fields' => $getFieldsParam($selectedFields),
            'ids' => $shownIds,
        ]);

        $response->assertStatus(200);

        $response->assertJson(fn ($json) => $json
            ->has('data', 2, fn ($json) => $json
                ->each(fn ($json) => $json
                    ->hasAll(...$selectedFields)
                )
            )
            ->etc()
        );
    }
    public function test_it_shows_detail_with_fields_as_array()
    {
        $this->it_shows_detail_with_fields(fn ($fields) => $fields);
    }

    public function test_it_shows_detail_with_fields_as_csv()
    {
        $this->it_shows_detail_with_fields(fn ($fields) => implode(',', $fields));
    }

    public function test_it_shows_listing_with_fields_as_array()
    {
        $this->it_shows_listing_with_fields(fn ($fields) => $fields);
    }

    public function test_it_shows_listing_with_fields_as_csv()
    {
        $this->it_shows_listing_with_fields(fn ($fields) => implode(',', $fields));
    }

    public function test_it_shows_multiple_with_fields_as_array()
    {
        $this->it_shows_listing_with_fields(fn ($fields) => $fields);
    }

    public function test_it_shows_multiple_with_fields_as_csv()
    {
        $this->it_shows_listing_with_fields(fn ($fields) => implode(',', $fields));
    }
}
