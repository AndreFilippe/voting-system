<?php

namespace Test;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Test\Utils\Mocks;

abstract class TestCase extends FrameworkTestCase
{
    use Mocks;

    public function __construct()
    {
        parent::__construct();
        require __DIR__ . '/../bootstrap/app.php';
    }

    protected function getProperty(&$object, string $property): mixed
    {
        $reflection = new \ReflectionClass(get_class($object));
        $test = $reflection->getProperty($property);
        $test->setAccessible(true);
        return $test->getValue($object);
    }

    protected function setDateTime(string $dateTime): void
    {
        Carbon::setTestNow($dateTime);
    }
}
