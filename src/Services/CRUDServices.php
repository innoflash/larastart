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
    function getParentRelationship()
    {
        return null;
    }

    /**
     * Deletes the model from the database
     */
    function destroy(string $message = 'Deleted successful!')
    {
        try {
            $this->getModel()->delete();
            return $this->successResponse($message);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Updates the model with the given filtered attributes, return the updated object if @* @param $returnObject
     */
    function update(array $attributes, string $message = 'Update successful!', bool $returnObject)
    {
        try {
            $model = $this->getModel()->update($this->optimizeAttributes($attributes));
            if (!$returnObject)
                return $this->successResponse($message);
            else
                return $model;
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Creates a new model with the given filtered attributes
     */
    function create(array $attributes, string $message = 'Created successfully!', bool $returnObject = false)
    {
        try {
            $model = $this->getModel()->create($this->optimizeAttributes($attributes));
            if (!$returnObject)
                return $this->successResponse($message);
            else
                return $model;
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Creates a new model from the given parent relationship
     */
    function createFromRelationship(array $attributes, string $message = 'Created successfully!', bool $returnObject = false)
    {
        $class = get_class($this->getModel());
        $model = new $class($this->optimizeAttributes($attributes));
        try {
            $this->getParent()->save($model);
            if (!$returnObject)
                return $this->successResponse($message);
            else
                return $model;
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Creates a new model from the given parent relationship
     */
    function createFromParent(array $attributes, string $message = 'Created successfully!', bool $returnObject = false)
    {
        return $this->createFromRelationship($attributes, $message, $returnObject);
    }

    /**
     * This removes unwanted fields from the incoming create/update requests
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
