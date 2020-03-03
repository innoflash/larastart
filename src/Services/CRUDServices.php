<?php

namespace InnoFlash\LaraStart\Services;

use Illuminate\Support\Str;
use InnoFlash\LaraStart\Http\Helper;
use InnoFlash\LaraStart\Traits\APIResponses;
use InvalidArgumentException;

abstract class CRUDServices
{
    use APIResponses;

    /**
     * This sets the attributes to be removed from the given set for updating or creating
     * @return mixed
     */
    abstract function getUnsetFields();

    /**
     * This get the model value or class of the model in the service
     * @return mixed
     */
    abstract function getModel();

    /**
     * This gets the relationship of the given model to the parent
     * @return mixed
     */
    public function getParentRelationship()
    {
        return null;
    }

    /**
     * Deletes the model from the database
     */
    public function destroy(string $message = 'Deleted successful!')
    {
        try {
            $this->getModel()->delete();
            return $this->successResponse($message);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     *  Updates the model with the given filtered attributes
     *
     * @param array $attributes
     * @param string $message
     * @param bool $returnObject
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update(array $attributes, string $message = 'Update successful!', bool $returnObject = false)
    {
        try {
            $this->getModel()->update($this->optimizeAttributes($attributes));
            if ($returnObject) return $this->getModel();
            return $this->successResponse($message);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Creates a new model with the given filtered attributes
     *
     * @param array $attributes
     * @param string $message
     * @param bool $returnObject
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(array $attributes, string $message = 'Created successfully!', bool $returnObject = false)
    {
        try {
            $model = $this->getModel()->create($this->optimizeAttributes($attributes));
            if ($returnObject)
                return $model;
            return $this->successResponse($message, [], 201);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Creates a new model from the given parent relationship
     *
     * @param array $attributes
     * @param string $message
     * @param bool $returnObject
     * @return \Illuminate\Http\JsonResponse
     */
    private function createFromRelationship(array $attributes, string $message = 'Created successfully!', bool $returnObject = false)
    {
        $class = get_class($this->getModel());
        $model = new $class($this->optimizeAttributes($attributes));
        try {
            $this->getParent()->save($model);
            if ($returnObject)
                return $model;
            return $this->successResponse($message, [], 201);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Creates a new model from the given parent relationship
     *
     * @param array $attributes
     * @param string $message
     * @param bool $returnObject
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFromParent(array $attributes, string $message = 'Created successfully!', bool $returnObject = false)
    {
        return $this->createFromRelationship($attributes, $message, $returnObject);
    }

    /**
     * This removes unwanted fields from the incoming create/update requests
     *
     * @param array $attributes
     * @return array
     */
    protected function optimizeAttributes(array $attributes)
    {
        if (is_string($this->getUnsetFields()))
            unset($attributes[$this->getUnsetFields()]);
        if (is_array($this->getUnsetFields()))
            foreach ($this->getUnsetFields() as $field) {
                unset($attributes[$field]);
            }
        return $attributes;
    }

    /**
     * Retrieves the parent to child relationship between this model and its parent
     */
    private function getParent()
    {
        if (\is_object($this->getParentRelationship())) return $this->getParentRelationship();
        else if (\is_array($this->getParentRelationship())) {
            $class = $this->getParentRelationship()['0'];
            $relationship = $this->getParentRelationship()['1'];
            if (sizeof($this->getParentRelationship()) > 2) $parent = $class::findOrFail(request($this->getParentRelationship()['2']));
            else {
                $_class = new $class();
                $parent = $class::findOrFail(request($_class->getForeignKey()));
            }
            return $parent->$relationship();
        } else throw new InvalidArgumentException('You have set an invalid parent for this model');
    }
}
