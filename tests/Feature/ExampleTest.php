<?php

namespace Tests\Feature;

test('basic test response', function () {
    $response = get('/');

    $response->assertStatus(200);
});
