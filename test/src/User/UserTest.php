<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\User\Domain\User;
use Src\User\Domain\ValueObject\UserWalletAmount;
use Src\Shared\Domain\Exception\BadRequestException;

class UserTest extends TestCase{

    private User $user;

    public function setUp(): void{
    }
    
    public function testCreateFromArrayComplete(){
        $arr = [
            'id' => '4',
            'fullName' => 'Juan Perez',
            'documentNumber' => '58965847',
            'email' => 'correo@dominio.com',
            'password' => 'pAssw0rd',
            'isMerchant' => 'true',
            'walletAmount' => '0.2',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->user = User::createFromArray($arr);

        $this->assertTrue($this->user->id->value() === 4);
        $this->assertTrue($this->user->fullName->value() === 'Juan Perez');
        $this->assertTrue($this->user->documentNumber->value() === '58965847');
        $this->assertTrue($this->user->email->value() === 'correo@dominio.com');
        $this->assertTrue($this->user->password->value() === 'pAssw0rd');
        $this->assertTrue($this->user->isMerchant->value() === true);
        $this->assertTrue($this->user->walletAmount->value() === 0.2);
        $this->assertTrue($this->user->createdAt->valueFormatISO() === '2024-04-12 14:10:10');
        $this->assertTrue($this->user->updatedAt->valueFormatISO() === '2024-04-12 14:10:15');
    }

    public function testCreateFromArrayIncomplete(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => '4',
            'fullName' => 'Juan Perez',
            'documentNumber' => '58965847',
            'email' => 'correo@dominio.com',
            'walletAmount' => '0.2',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:10'
        ];

        $this->user = User::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid1(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => -8,
            'fullName' => '',
            'documentNumber' => '58965847',
            'email' => 'correo@dominio.com',
            'password' => 'pAssw0rd',
            'isMerchant' => true,
            'walletAmount' => '0.2',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:10'
        ];

        $this->user = User::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid2(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => 4,
            'fullName' => 'Juan Perez',
            'documentNumber' => '58965847',
            'email' => 'correo@',
            'password' => 'pAssw0rd',
            'isMerchant' => true,
            'walletAmount' => '0.2',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:10'
        ];

        $this->user = User::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid3(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => 4,
            'fullName' => 'Juan Perez',
            'documentNumber' => '58965847',
            'email' => 'correo@dominio.com',
            'password' => '',
            'isMerchant' => true,
            'walletAmount' => '0.2',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:10'
        ];

        $this->user = User::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid4(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => 10,
            'fullName' => 'Juan Perez',
            'documentNumber' => '58965847',
            'email' => 'email@domain.com',
            'password' => 'pAssw0rd',
            'isMerchant' => 5,
            'walletAmount' => '0.2',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:10'
        ];

        $this->user = User::createFromArray($arr);
    }

    public function testCreateFromArrayInvalid5(){
        $this->expectException(BadRequestException::class);

        $arr = [
            'id' => 10,
            'fullName' => null,
            'documentNumber' => '58965847',
            'email' => 5,
            'password' => 'pAssw0rd',
            'isMerchant' => true,
            'walletAmount' => '0.2',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:10'
        ];

        $this->user = User::createFromArray($arr);
    }
    
    public function testSendPayment(){
        $arr = [
            'id' => '4',
            'fullName' => 'Juan Perez',
            'documentNumber' => '58965847',
            'email' => 'correo@dominio.com',
            'password' => 'pAssw0rd',
            'isMerchant' => 'false',
            'walletAmount' => '10',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->user = User::createFromArray($arr);

        $this->user->sendPayment(new UserWalletAmount(4));

        $this->assertEquals($this->user->walletAmount->value(), 6);
    }

    public function testReceivePayment(){
        $arr = [
            'id' => '4',
            'fullName' => 'Juan Perez',
            'documentNumber' => '58965847',
            'email' => 'correo@dominio.com',
            'password' => 'pAssw0rd',
            'isMerchant' => 'false',
            'walletAmount' => '10',
            'createdAt' => '2024-04-12 14:10:10',
            'updatedAt' => '2024-04-12 14:10:15'
        ];

        $this->user = User::createFromArray($arr);

        $this->user->receivePayment(new UserWalletAmount(4));

        $this->assertEquals($this->user->walletAmount->value(),  14);
    }
}