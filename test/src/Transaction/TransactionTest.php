<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Transaction\Domain\Transaction;
use Src\Shared\Domain\Exception\BadRequestException;

class TransactionTest extends TestCase{

    private Transaction $transaction;

    public function setUp(): void{
    }
    
    public function testCreateFromArrayComplete(){
        $arr = [
            'id' => '4',
            'amount' => '15.78',
            'payerUserId' => '8',
            'payeeUserId' => '5',
            'isNotified' => 'false',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->transaction = Transaction::createFromArray($arr);

        $this->assertEquals($this->transaction->id->value(), 4);
        $this->assertEquals($this->transaction->amount->value(), 15.78);
        $this->assertEquals($this->transaction->payerUserId->value(), 8    );
        $this->assertEquals($this->transaction->payeeUserId->value(), 5);
        $this->assertEquals($this->transaction->isNotified->value(), false);
        $this->assertEquals($this->transaction->createdAt->valueFormatISO(), '2024-04-12 14:10:10');
        $this->assertEquals($this->transaction->updatedAt->valueFormatISO(), '2024-04-12 14:10:15');
    }

    public function testCreateFromArrayIncomplete(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => '4',
            'amount' => '15.78',
            'payeeUserId' => '5',
            'isNotified' => 'false',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->transaction = Transaction::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid1(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => -6,
            'amount' => '15.78',
            'payerUserId' => '8',
            'payeeUserId' => '5',
            'isNotified' => 'false',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->transaction = Transaction::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid2(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => 9,
            'amount' => '15.78',
            'payerUserId' => '8',
            'payeeUserId' => 'abc',
            'isNotified' => 'false',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->transaction = Transaction::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid3(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => -6,
            'amount' => '15.78',
            'payerUserId' => '8',
            'payeeUserId' => '5',
            'isNotified' => 'false',
            'createdAt' => 'abc',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->transaction = Transaction::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid4(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => -6,
            'amount' => '15.78',
            'payerUserId' => '8',
            'payeeUserId' => '5',
            'isNotified' => 'abc',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->transaction = Transaction::createFromArray($arr);
    }
}