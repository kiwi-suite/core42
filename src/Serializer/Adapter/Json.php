<?php
namespace Core42\Serializer\Adapter;

use Core42\Hydrator\Strategy\Service\JsonHydratorPluginManager;
use Core42\Serializer\JsonSerializerInterface;
use Zend\Serializer\Adapter\Json as ZendJson;

class Json extends ZendJson
{
    /**
     * @var JsonHydratorPluginManager
     */
    protected $hydratorPluginManager;

    /**
     * Json constructor.
     * @param array|null|\Traversable|\Zend\Serializer\Adapter\AdapterOptions $options
     * @param JsonHydratorPluginManager $hydratorPluginManager
     */
    public function __construct($options, JsonHydratorPluginManager $hydratorPluginManager)
    {
        parent::__construct($options);

        $this->hydratorPluginManager = $hydratorPluginManager;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function serialize($value)
    {
        $value = $this->serializeRecursive($value);

        return parent::serialize($value);
    }

    /**
     * @param string $json
     * @return mixed
     */
    public function unserialize($json)
    {
        $value = parent::unserialize($json);

        return $this->unserializeRecursive($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function serializeRecursive($value)
    {
        if (is_object($value)) {
            return $this->serializeObject($value);
        }

        if (is_array($value)) {
            foreach ($value as &$val) {
                $val = $this->serializeRecursive($val);
            }
        }

        return $value;
    }

    /**
     * @param $value
     * @return array
     */
    private function serializeObject($value)
    {
        $className = get_class($value);

        if ($value instanceof JsonSerializerInterface) {
            $values = $value->getJsonArray();
            $values['@type'] = $className;

            return $values;
        }

        if ($this->hydratorPluginManager->has($className)) {
            return $this->hydratorPluginManager->get($className)->extract($value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function unserializeRecursive($value)
    {
        if (!is_array($value)) {
            return $value;
        }

        if (isset($value['@type'])) {
            return $this->unserializeObject($value);
        }

        foreach ($value as &$val) {
            $val = $this->unserializeRecursive($val);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function unserializeObject($value)
    {
        if ($this->hydratorPluginManager->has($value['@type'])) {
            return $this->hydratorPluginManager->get($value['@type'])->hydrate($value);
        }

        if (class_exists($value['@type'])) {
            $class = new $value['@type']();
            if ($class instanceof JsonSerializerInterface) {
                return $class->fromJsonArray($value);
            }
        }

        return $value;
    }
}
