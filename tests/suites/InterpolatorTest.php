<?php

declare(strict_types=1);

namespace Phrity\Util\Interpolator\Test;

use DateTime;
use PHPUnit\Framework\TestCase;
use Phrity\Util\Interpolator\Interpolator;
use Phrity\Util\Transformer\JsonDecoder;
use RuntimeException;

/**
 * Interpolator test class.
 */
class InterpolatorTest extends TestCase
{
    public function testSimpleInterpolation(): void
    {
        $interpolator = new Interpolator();
        $replacers = [
            'a' => 'first',
            'b' => 'second',
        ];
        $result = $interpolator->interpolate('Interpolating {a} and {b}.');
        $this->assertEquals('Interpolating {a} and {b}.', $result);
        $result = $interpolator->interpolate('Interpolating {a} and {b}.', $replacers);
        $this->assertEquals('Interpolating first and second.', $result);
        $result = $interpolator->interpolate('Interpolating {{a}} and {{b}.', $replacers);
        $this->assertEquals('Interpolating {first} and {second.', $result);
    }

    public function testArrayInterpolation(): void
    {
        $interpolator = new Interpolator();
        $replacers = [
            'a' => [
                'a' => 'first',
                'b' => 'second',
            ],
        ];
        $result = $interpolator->interpolate('Interpolating {a}.', $replacers);
        $this->assertEquals('Interpolating array.', $result);
        $result = $interpolator->interpolate('Interpolating {a.a} and {a.b}.', $replacers);
        $this->assertEquals('Interpolating first and second.', $result);
        $result = $interpolator->interpolate('Interpolating {a.a.a}.', $replacers);
        $this->assertEquals('Interpolating {a.a.a}.', $result);
    }

    public function testObjectInterpolation(): void
    {
        $interpolator = new Interpolator();
        $replacers = (object)[
            'a' => (object)[
                'a' => 'first',
                'b' => 'second',
            ],
        ];
        $result = $interpolator->interpolate('Interpolating {a}.', $replacers);
        $this->assertEquals('Interpolating stdClass.', $result);
        $result = $interpolator->interpolate('Interpolating {a.a} and {a.b}.', $replacers);
        $this->assertEquals('Interpolating first and second.', $result);
        $result = $interpolator->interpolate('Interpolating {a.a.a}.', $replacers);
        $this->assertEquals('Interpolating {a.a.a}.', $result);
    }

    public function testScalarInterpolation(): void
    {
        $interpolator = new Interpolator();
        $replacers = [
            'int' => 123,
            'float' => 45.67,
            'true' => true,
            'false' => false,
            'null' => null,
        ];
        $result = $interpolator->interpolate('{int}, {float}, {true}, {false}, {null}.', $replacers);
        $this->assertEquals('123, 45.67, true, false, null.', $result);
    }

    public function testExceptionInterpolation(): void
    {
        $interpolator = new Interpolator();
        $replacers = [
            'exception' => new RuntimeException('Error message', 666),
        ];
        $result = $interpolator->interpolate('{exception}.', $replacers);
        $this->assertEquals('Error message.', $result);
        $result = $interpolator->interpolate('{exception.message}, {exception.code}, {exception.type}.', $replacers);
        $this->assertEquals('Error message, 666, RuntimeException.', $result);
    }

    public function testSetSeparator(): void
    {
        $interpolator = new Interpolator(separator: '/');
        $replacers = ['a' => ['b' => ['c' => 'test']]];
        $result = $interpolator->interpolate('Interpolating {a/b/c}.', $replacers);
        $this->assertEquals('Interpolating test.', $result);
    }

    public function testSetTransformer(): void
    {
        $transformer = new JsonDecoder();
        $interpolator = new Interpolator(transformer: $transformer);
        $replacers = ['a' => '{"b": "test", "c": 1234}'];
        $result = $interpolator->interpolate('Interpolating {a.b} and {a.c}.', $replacers);
        $this->assertEquals('Interpolating test and 1234.', $result);
    }
}
