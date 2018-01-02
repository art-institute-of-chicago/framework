<?php

namespace Aic\Hub\Foundation;

use Aic\Hub\Foundation\ResourceSerializer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

class ResourceServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Provide methods for API transformers
        $fractal = $this->app->make('League\Fractal\Manager');

        $fractal->setSerializer( new ResourceSerializer );

        if (isset($_GET['include']))
        {
            $includes = $_GET['include'];
            $includes = snake_case( camel_case( $includes ) );
            $fractal->parseIncludes( $includes );
        }

        if (isset($_GET['exclude']))
        {
            $excludes = $_GET['exclude'];
            $excludes = snake_case( camel_case( $excludes ) );
            $fractal->parseExcludes( $excludes );
        }

        response()->macro('item', function ($item, TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal) {

            $resource = new Item($item, $transformer);

            return response()->json(
                $fractal->createData($resource)->toArray(),
                $status,
                $headers
            );

        });

        response()->macro('collection', function ($collection, TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal) {

            $resource = new Collection($collection, $transformer);

            $data = $fractal->createData($resource)->toArray();

            if ($collection instanceof LengthAwarePaginator)
            {

                $paginator = [
                    'total' => $collection->total(),
                    'limit' => (int) $collection->perPage(),
                    'offset' => (int) $collection->perPage() * ( $collection->currentPage() - 1 ),
                    'total_pages' => $collection->lastPage(),
                    'current_page' => $collection->currentPage(),
                ];

                // TODO: Account for include, fields, and other stuff?

                if ($collection->previousPageUrl()) {
                    $paginator['prev_url'] = $collection->previousPageUrl() . '&limit=' . $collection->perPage();
                }

                if ($collection->hasMorePages()) {
                    $paginator['next_url'] = $collection->nextPageUrl() . '&limit=' . $collection->perPage();
                }

                $data = array_merge(['pagination' => $paginator], $data);

            }

            return response()->json(
                $data,
                $status,
                $headers
            );

        });

        // MySQL compatibility
        Schema::defaultStringLength(191);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

}
