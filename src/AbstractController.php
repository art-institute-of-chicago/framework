<?php

namespace Aic\Hub\Foundation;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Input;
use Closure;

use Aic\Hub\Foundation\Exceptions\BigLimitException;
use Aic\Hub\Foundation\Exceptions\InvalidSyntaxException;
use Aic\Hub\Foundation\Exceptions\ItemNotFoundException;
use Aic\Hub\Foundation\Exceptions\MethodNotAllowedException;
use Aic\Hub\Foundation\Exceptions\TooManyIdsException;

use Illuminate\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController
{

    protected $model;

    protected $transformer;


    /**
     * A maximum of this many items will be shown per page before erroring.
     * You might run into URL length limits when requesting multiple IDs.
     *
     * @var integer
     */
    const LIMIT_MAX = 1000;


    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        return $this->select( $request, function( $id ) {

            return $this->find($id);

        });

    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return $this->collect( $request, function( $limit ) {

            return $this->paginate( $limit );

        });

    }


    /**
     * Display the specified resource, but use the route name as a scope on the model.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $id
     * @return \Illuminate\Http\Response
     */
    protected function showScope( Request $request, $id )
    {

        $scope = $this->getScope( $request, -2 );

        return $this->select( $request, function( $id ) use ( $scope ) {

            return ($this->model)::$scope()->find($id);

        });

    }


    /**
     * Display a listing of the resource, but use the route name as a scope on the model.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function indexScope( Request $request )
    {

        $scope = $this->getScope( $request, -1 );

        return $this->collect( $request, function( $limit ) use ( $scope ) {

            return ($this->model)::$scope()->paginate($limit);

        });

    }


    /**
     * Extract name of scope method from request string.
     * Ensures that the method is a valid local scope.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $offset  Index of the Request URI segment to extract
     * @return string
     */
    protected function getScope( Request $request, $offset )
    {

        $param = array_slice( $request->segments(), $offset, 1 )[0];
        $param = str_replace( ' ', '', ucwords( str_replace( '-', ' ', $param ) ) );

        $scope = lcfirst( $param );
        $method = 'scope' . $scope;

        if( !method_exists( $this->model, $method ) )
        {
            throw new \BadFunctionCallException( 'Class ' . $this->model . ' has no scope named `' . $scope . '`' );
        }

        return $scope;

    }


    /**
     * Call to find specific id(s). Override this method when logic to get
     * a model is more complex than a simple `$model::find($id)` call.
     *
     * @param mixed $ids
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function find($ids)
    {

        return ($this->model)::find($ids);

    }


    /**
     * Call to get a model list. Override this method when logic to get
     * models is more complex than a simple `$model::paginate($limit)` call.
     *
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function paginate($limit)
    {

        return ($this->model)::paginate($limit);

    }


    /**
     * Return a single resource. Not meant to be called directly in routes.
     * `$callback` should return an Eloquent Model.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $callback
     * @return \Illuminate\Http\Response
     */
    protected function select( Request $request, Closure $callback )
    {

        $this->validateMethod( $request );

        $id = $request->route('id');

        if (!$this->validateId( $id ))
        {
            throw new InvalidSyntaxException();
        }

        $item = $callback( $id );

        if (!$item)
        {
            throw new ItemNotFoundException();
        }

        $fields = Input::get('fields');

        return response()->item($item, new $this->transformer($fields) );

    }


    /**
     * Return a list of resources. Not meant to be called directly in routes.
     * `$callback` should return an Eloquent Collection.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $callback
     * @return \Illuminate\Http\Response
     */
    protected function collect( Request $request, Closure $callback )
    {

        $this->validateMethod( $request );

        // Process ?ids= query param
        $ids = $request->input('ids');

        if ($ids)
        {
            return $this->showMutliple($ids);
        }

        // Check if the ?limit= is too big
        $limit = $request->input('limit') ?: 12;

        if ($limit > static::LIMIT_MAX)
        {
            throw new BigLimitException();
        }

        // This would happen for subresources
        $id = $request->route('id');

        // Assumes the inheriting class set model and transformer
        $all = $callback( $limit, $id );

        $fields = Input::get('fields');

        return response()->collection($all, new $this->transformer($fields) );

    }


    /**
     * Display multiple resources.
     *
     * @param string $ids
     * @return \Illuminate\Http\Response
     */
    protected function showMutliple($ids = '')
    {

        // TODO: Accept an array, not just comma-separated string
        $ids = explode(',', $ids);

        if (count($ids) > static::LIMIT_MAX)
        {
            throw new TooManyIdsException();
        }

        // Validate the syntax for each $id
        foreach( $ids as $id )
        {

            if (!$this->validateId( $id ))
            {
                throw new InvalidSyntaxException();
            }

        }

        $all = $this->find($ids);

        $fields = Input::get('fields');

        return response()->collection($all, new $this->transformer($fields) );

    }


    /**
     * Validate `id` route or query string param format.
     *
     * @param mixed $id
     * @return boolean
     */
    protected function validateId( $id )
    {

        // Only execute this validation if the model has defined a `validateId` method
        if (method_exists($this->model, 'validateId'))
        {
            return $this->model::validateId($id);
        }
        return true;

    }


    /**
     * Throw an exception if the HTTP method is invalid.
     *
     * @param  \Illuminate\Http\Request $request
     * @return boolean
     */
    protected function validateMethod( Request $request )
    {

        // Technically this will never be called if we only route GET and POST
        // To respond to all HTTP verbs, call `Route.any`
        if( !in_array($request->method(), ['GET', 'POST']) )
        {
            throw new MethodNotAllowedException();
        }

    }


    /**
     * Utility getter for transformer assoc. w/ this controller.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function transformer() {

        return $this->transformer;

    }


    /**
     * Utility getter for model assoc. w/ this controller.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model() {

        return $this->model;

    }

}
