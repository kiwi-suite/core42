<?php
namespace Core42\Serializer;

interface JsonSerializerInterface
{
    /**
     * @return array
     */
    public function getJsonArray();

    /**
     * @param array $values
     * @return mixed
     */
    public function fromJsonArray(array $values);
}
