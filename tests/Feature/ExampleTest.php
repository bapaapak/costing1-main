<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }
}
