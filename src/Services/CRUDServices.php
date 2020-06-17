<?php

namespace InnoFlash\LaraStart\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InnoFlash\LaraStart\Traits\APIResponses;
use InvalidArgumentException;

abstract class CRUDServices
{
    use APIResponses;

    /**
     * This sets the attributes to be removed from the given set for updating or creating.
     * @return array
     */
    abstract public function getUnsetFields(): array;

    /**
     * This get the model value or class of the model in the service.
     * @return mixed
     */
    abstract public function getModel();

    /**
     * This gets the relationship of the given model to the parent.
     *
     * @return mixed
     */
    public function getParentRelationship()
    {
    }

    /**
     * Deletes the model from the database.
     *
     * @param  string  $message
     *
     * @return
     */
    public function destroy(string $message = 'Deleted successful!')
    {
        try {
            $this->getModel()->delete();

            if (config('larastart.return_object')) {
                return '';
            }

            return $this->successResponse($message, [], 204);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     *  Updates the model with the given filtered attributes.
     *
     * @param  array  $attributes
     * @param  string  $message
     * @param  bool  $returnObject
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update(array $attributes, string $message = 'Update successful!', bool $returnObject = false)
    {
        try {
            $this->getModel()->update($this->optimizeAttributes($attributes));
            if ($returnObject || config('larastart.return_object')) {
                return $this->getModel();
            }

            return $this->successResponse($message);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Creates a new model with the given filtered attributes.
     *
     * @param  array  $attributes
     * @param  string  $message
     * @param  bool  $returnObject
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(array $attributes, string $message = 'Created successfully!', bool $returnObject = false)
    {

        try {
            $model = $this->getModelClassName()::create($this->optimizeAttributes($attributes));

            if ($returnObject || config('larastart.return_object')) {
                return $model;
            }

            return $this->successResponse($message, [], 201);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Creates a new model from the given parent relationship.
     *
     * @param  array  $attributes
     * @param  string  $message
     * @param  bool  $returnObject
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFromParent(
        array $attributes,
        string $message = 'Created successfully!',
        bool $returnObject = false
    ) {
        $class = $this->getModelClassName();
        $model = new $class($this->optimizeAttributes($attributes));

        try {
            $this->getParent()->save($model);
            if ($returnObject || config('larastart.return_object')) {
                return $model;
            }

            return $this->successResponse($message, [], 201);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * This removes unwanted fields from the incoming create/update requests.
     *
     * @param  array  $attributes
     *
     * @return array
     */
    protected function optimizeAttributes(array $attributes)
    {
        return collect($attributes)
            ->reject($this->getUnsetFields())
            ->toArray();
    }

    /**
     * Retrieves the parent to child relationship between this model and its parent.
     */
    private function getParent()
    {
        if (\is_object($this->getParentRelationship())) {
            return $this->getParentRelationship();
        } elseif (\is_array($this->getParentRelationship())) {
            $class = $this->getParentRelationship()['0'];
            $relationship = $this->getParentRelationship()['1'];

            if (count($this->getParentRelationship()) > 2) {
                $parent = $class::findOrFail(request($this->getParentRelationship()['2']));
            } else {
                $_class = new $class();
                $parent = $class::findOrFail(request($_class->getForeignKey()));
            }

            return $parent->$relationship();
        } else {
            throw new InvalidArgumentException('You have set an invalid parent for this model');
        }
    }

    /**
     * Return the class name of the service main model.
     *
     * @return string
     */
    protected function getModelClassName(): string
    {
        $modelVariable = collect(get_object_vars($this))
            ->reject(fn($var) => ! ($var instanceof Model))
            ->filter(function ($var, $key) {
                return Str::contains('get'.Str::ucfirst($key), get_class_methods($this))
                    && Str::startsWith(Str::lower(class_basename($this)), $key);
            })->first();

        return get_class($modelVariable);
    }
}
