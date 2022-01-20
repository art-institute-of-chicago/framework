<?php

namespace Aic\Hub\Foundation;

use Illuminate\Database\Eloquent\Model;

use League\Fractal\TransformerAbstract;

abstract class AbstractTransformer extends TransformerAbstract
{

    /**
     * Used for only returning a subset of fields.
     * Expects a comma-separated string or an array.
     *
     * @link https://github.com/thephpleague/fractal/issues/226
     *
     * @var string
     */
    protected $fields;

    /**
     * Be sure to call parent::__construct() if you overwrite this.
     * Otherwise, you will lose field-filtering functionality.
     */
    public function __construct($fields = null)
    {

        $this->fields = $this->getFields($fields);

    }

    /**
     * Parse out the fields variable passed via constructor.
     * Expects a comma-separated string or an array.
     *
     * @var array
     */
    private function getFields($fields = null)
    {

        if (!$fields) {
            return null;
        }

        if (is_array($fields)) {
            return $fields;
        }

        if (is_string($fields)) {
            return explode(',', $fields);
        }

        return null;

    }

    /**
     * Turn this item object into a generic array.
     *
     * Be sure to call parent::transform() if you overwrite this.
     * Otherwise, you will lose field-filtering functionality.
     *
     * @param  mixed  $input
     * @return array
     */
    public function transform($input)
    {

        if ($input instanceof Model)
        {
            $input = $input->toArray();
        }

        if (!is_array($input))
        {
            throw \InvalidArgumentException('Transformer expects array or model.');
        }

        return $this->filterFields($input);

    }

    /**
     * Filter the fields of this item for output.
     *
     * @param  mixed  $data
     * @return array
     */
    protected function filterFields($data)
    {

        if (is_null($this->fields)) {
            return $data;
        }

        // Unset default includes not present in fields param
        // https://github.com/thephpleague/fractal/issues/143
        $this->setDefaultIncludes(array_intersect($this->defaultIncludes, $this->fields));

        // Filter $data to only provide keys specified in fields param
        return array_intersect_key($data, array_flip((array) $this->fields));

    }

}
