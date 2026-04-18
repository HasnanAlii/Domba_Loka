<?php

use function Pest\Laravel\get;

test('guest is redirected from dashboard to login', function () {
    $response = get(route('dashboard'));

    $response->assertRedirect(route('login'));
});
