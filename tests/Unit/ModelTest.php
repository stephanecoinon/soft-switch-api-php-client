<?php

namespace Tests\Unit;

use StephaneCoinon\SoftSwitch\Model;
use Tests\TestCase;

class ModelTest extends TestCase
{
    /** @test */
    public function it_ignores_numeric_attribute_names()
    {
        $model = new Model([
            0 => 'John',
            'name' => 'John',
            '1' => 'john@example.com',
            'email' => 'john@example.com',
        ]);

        $this->assertEquals(
            [
                'name' => 'John',
                'email' => 'john@example.com',
            ],
            $model->getAttributes()
        );
    }

    /** @test */
    public function it_returns_a_default_value_when_attempting_to_get_a_non_existing_attribute()
    {
        $model = new Model(['foo' => 42]);

        $this->assertNull($model->getAttribute('bar'));
    }

    /** @test */
    public function attributes_can_be_retrieved_as_an_instance_property()
    {
        $model = new Model(['foo' => 42]);

        $this->assertEquals(42, $model->foo);
    }
}
