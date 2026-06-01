<?php

namespace Tests\Unit\app\Support\Dto;

use App\Support\Dto\Dto;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DtoTest extends TestCase
{
    #[Test]
    public function testCamelCaseDtoSuccessMapping(): void
    {
        $dto = new class([
            'testCamelCase' => 10,
        ]) extends Dto {
            public int $testCamelCase;
        };

        $this->assertEquals(10, $dto->testCamelCase);
    }

    #[Test]
    public function testSneakCaseSuccessMappingToCamelCaseDto(): void
    {
        $dto = new class([
            'test_sneak_case' => 20,
        ]) extends Dto {
            public int $testSneakCase;
        };

        $this->assertEquals(20, $dto->testSneakCase);
    }

    #[Test]
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

    #[Test]
    public function testDtoCamelCaseToSneakCaseSuccess(): void
    {
        $array = [
            'first_camel_case' => 10,
            'second_camel_case' => 20,
            'third_camel_case' => 30,
        ];

        $dto = new class($array) extends Dto {
            public int $firstCamelCase;
            public int $secondCamelCase;
            public int $thirdCamelCase;
        };

        $this->assertEquals($array, $dto->toSneakedCaseArray());
        $this->assertEquals(10, $dto->firstCamelCase);
        $this->assertEquals(20, $dto->secondCamelCase);
        $this->assertEquals(30, $dto->thirdCamelCase);
    }
}
