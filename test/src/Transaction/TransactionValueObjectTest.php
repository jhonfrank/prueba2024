<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Transaction\Domain\ValueObject\{TransactionId, TransactionAmount, TransactionPayerUserId, TransactionPayeeUserId, TransactionIsNotified, TransactionCreatedAt, TransactionUpdatedAt};
use Src\Shared\Domain\Exception\BadRequestException;

class TransactionValueObjectTest extends TestCase{

    private TransactionId $id;
    private TransactionAmount $amount;
    private TransactionPayerUserId $payerUserId;
    private TransactionPayeeUserId $payeeUserId;
    private TransactionIsNotified $isNotified;
    private TransactionCreatedAt $createdAt;
    private TransactionUpdatedAt $updatedAt;

    public function setUp(): void{
    }

    public function testCreateIdWithNegative(){
        $this->expectException(BadRequestException::class);
        $this->id = new TransactionId(-10);
    }

    public function testCreateAmountWithNegative(){
        $this->expectException(BadRequestException::class);
        $this->amount = new TransactionAmount(-10);
    }

    public function testCreatePayerUserIdWithNegative(){
        $this->expectException(BadRequestException::class);
        $this->payerUserId = new TransactionPayerUserId(-10);
    }

    public function testCreatePayeeUserIdWithNegative(){
        $this->expectException(BadRequestException::class);
        $this->payeeUserId = new TransactionPayeeUserId(-10);
    }
}