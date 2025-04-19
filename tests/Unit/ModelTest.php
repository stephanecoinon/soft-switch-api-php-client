<?php

declare(strict_types=1);

use StephaneCoinon\SoftSwitch\Model;

it('ignores numeric attribute names', function () {
    $model = new ModelStub([
        0 => 'John',
        'name' => 'John',
        '1' => 'john@example.com',
        'email' => 'john@example.com',
    ]);

    expect($model->getAttributes())->toEqual([
        'name' => 'John',
        'email' => 'john@example.com',
    ]);
});

it('returns a default value when attempting to get a non-existing attribute', function () {
    $model = new ModelStub(['foo' => 42]);

    expect($model->getAttribute('bar'))->toBeNull();
});

it('retrieves attributes as an instance property', function () {
    $model = new ModelStub(['foo' => 42]);

    expect($model->foo)->toEqual(42);
});

class ModelStub extends Model
{
}
