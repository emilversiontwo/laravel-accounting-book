<?php

namespace Tests\Unit\app\Support\Dto;

use App\Support\Dto\Dto;
use PHPUnit\Framework\TestCase;

class DtoTest extends TestCase
{
    public function testCamelCaseDtoSuccessMapping(): void
    {
        $dto = new class([
            'testCamelCase' => 10,
        ]) extends Dto {
            public int $testCamelCase;
        };

        $this->assertEquals(10, $dto->testCamelCase);
    }

    public function testSneakCaseSuccessMappingToCamelCaseDto(): void
    {
        $dto = new class([
            'test_sneak_case' => 20,
        ]) extends Dto {
            public int $testSneakCase;
        };

        $this->assertEquals(20, $dto->testSneakCase);
    }

    public function testDtoToArraySuccess(): void
    {
        $array = [
            'first' => 10,
            'second' => 20,
        ];

        $dto = new class($array) extends Dto {
            public int $first;
            public int $second;
        };

        $this->assertEquals($array, $dto->toArray());
        $this->assertEquals(10, $dto->first);
        $this->assertEquals(20, $dto->second);
    }
}
