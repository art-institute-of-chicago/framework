<?php

namespace Aic\Hub\Foundation;

use League\Fractal\Serializer\DataArraySerializer;

class ResourceSerializer extends DataArraySerializer
{

   /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {

        if ($resourceKey === false) {
            return $data;
        }

        return array($resourceKey ?: 'data' => $data);

    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {

        if ($resourceKey === false) {
            return $data;
        }

        return array($resourceKey ?: 'data' => $data);

    }

}
