<?php

namespace InnoFlash\LaraStart\Services;

use InnoFlash\LaraStart\Traits\APIResponses;



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
    function destroy(string $message = 'Deleted successful!')
    {
        try {
            $this->getModel()->delete();
            return $this->successResponse($message);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
    function update(array $attributes, string $message = 'Update successful!')
    {
        try {
            $this->getModel()->update($this->optimizeAttributes($attributes));
            return $this->successResponse($message);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
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
    function createFromRelationship(array $attributes, string $message = 'Created successfully!', bool $returnObject = false)
    {
        $class = get_class($this->getModel());
        $model = new $class($this->optimizeAttributes($attributes));
        try {
            $this->getParentRelationship()->save($model);
            if (!$returnObject)
                return $this->successResponse($message);
            else
                return $model;
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
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
}
