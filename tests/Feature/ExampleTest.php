<?php

pest()->group('example');

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
});
