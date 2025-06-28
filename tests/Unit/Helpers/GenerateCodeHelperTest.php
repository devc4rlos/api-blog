<?php

namespace Tests\Unit\Helpers;

use App\Helpers\GenerateCodeHelper;
use Tests\TestCase;

class GenerateCodeHelperTest extends TestCase
{
    public function test_should_generate_code()
    {
        $code = GenerateCodeHelper::generate();

        $this->assertIsString($code);
        $this->assertEquals(6, strlen($code));
    }
}
