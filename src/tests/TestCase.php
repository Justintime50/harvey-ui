<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static $expireCassetteDays = 180;
    protected static $testProjectName = 'justintime50-justinpaulhammond';
    protected static $testProjectId = '1';

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}
