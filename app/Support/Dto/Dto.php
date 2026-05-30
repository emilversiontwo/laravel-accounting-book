<?php

declare(strict_types=1);

namespace App\Support\Dto;

use App\Support\Dto\Contracts\DtoInterface;
use Iterator;

abstract class Dto implements DtoInterface
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;

                continue;
            }
            $camelKey = $this->snakeCaseToCamelCase($key);
            if (property_exists($this, $camelKey)) {
                $this->{$camelKey} = $value;
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }



    private function snakeCaseToCamelCase(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
}
