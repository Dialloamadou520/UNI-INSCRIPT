<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_pages_publiques_sont_accessibles(): void
    {
        foreach (['accueil', 'presentation', 'comment-ca-marche'] as $route) {
            $this->get(route($route))->assertOk();
        }
    }
}
