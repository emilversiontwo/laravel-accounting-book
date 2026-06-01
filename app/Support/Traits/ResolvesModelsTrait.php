<?php

namespace App\Support\Traits;

use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ResolvesModelsTrait
{

    /**
     * Find Model entry or throw
     * @param class-string<Model> $modelClass
     * @param int $id
     * @param string|null $message
     * @return Model
     * @throws NotFoundException
     */
    protected function resolveOrFail(string $modelClass, int $id, string $message = null): Model
    {
        try {
            return $modelClass::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $message = $message ?? class_basename($modelClass) . " with id {$id} not found";
            throw NotFoundException::make($message, $e);
        }
    }
}
