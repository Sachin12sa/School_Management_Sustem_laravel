<?php

namespace Tests\Feature\View\MyBook\Blade;

use Tests\TestCase;

class PhpTest extends TestCase
{
    /**
     * A basic view test example.
     */
    public function test_it_can_render(): void
    {
        $contents = $this->view('my_book.blade.php', [
            //
        ]);

        $contents->assertSee('');
    }
}
