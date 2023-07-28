<?php

declare(strict_types=1);

namespace Tests\Feature;

test('Not debugging statements are left in our code.')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

test('Use strict types declaration in files.')
    ->expect('App')
    ->toUseStrictTypes();
