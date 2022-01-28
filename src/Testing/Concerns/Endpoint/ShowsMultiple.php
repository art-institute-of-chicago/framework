<?php

namespace Aic\Hub\Foundation\Testing\Concerns\Endpoint;

trait ShowsMultiple
{
    /**
     * The `ids` could be an array, or a string with comma-separated ids.
     */
    private function it_shows_multiple(callable $getIdsParam)
    {
        $items = ($this->model)::factory()->count(17)->create()->shuffle();

        $key = $items->first()->getKeyName();
        $shownIds = $items->slice(0, 13)->pluck($key)->all();

        $response = $this->getJson($this->endpoint . '?' . http_build_query([
            'ids' => $getIdsParam($shownIds),
        ]));

        $response->assertStatus(200);

        $response->assertJson(fn ($json) => $json
            ->has('data', 13)
            ->etc()
        );

        $returnedIds = collect($response->getData()->data)->pluck('id')->all();

        $this->assertEqualsCanonicalizing($shownIds, $returnedIds);
    }

    public function test_it_shows_multiple_with_ids_as_array()
    {
        $this->it_shows_multiple(fn ($ids) => $ids);
    }

    public function test_it_shows_multiple_with_ids_as_csv()
    {
        $this->it_shows_multiple(fn ($ids) => implode(',', $ids));
    }
}
