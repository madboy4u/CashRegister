<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\CashRegisterController;

class CashRegisterTest extends TestCase
{
    protected CashRegisterController $register;
    /**
     * A test CashRegisterClass.
     *
     * @return void
     */
    protected function setUp(): void
    {
    }

    public function testaddItem()
    {
        $row = ["00000000006" => 4, "00000000002" => 6, "00000000009" => 1]; // Mock row data

        $register = new CashRegisterController(0);

        $register->addItem("00000000006",1);
        $register->addItem("00000000002",2);
        $register->addItem("00000000009",1);
        $register->addItem("00000000006",3);
        $register->addItem("00000000002",4);

        $this->assertEquals($row, $register->getRowSales());

    }

    public function testaddItem2()
    {

        $row = ["00000000005" => 4, "00000000010" => 6, "00000000009" => 1]; // Mock row data

        $register2 = new CashRegisterController(0);

        $register2->addItem("00000000005",1);
        $register2->addItem("00000000010",2);
        $register2->addItem("00000000009",1);
        $register2->addItem("00000000005",3);
        $register2->addItem("00000000010",4);

        $this->assertEquals($row, $register2->getRowSales());
    }
}