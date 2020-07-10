<?php
namespace App\Tests\Service;

use App\Service\CalculatorService;
use PHPUnit\Framework\TestCase;

class CalculatorServiceTest extends TestCase
{
    public function testAdd(){
        $calculator = new CalculatorService();
        $result = $calculator->add(5,10);

        $this->assertEquals(15, $result);

    }

}