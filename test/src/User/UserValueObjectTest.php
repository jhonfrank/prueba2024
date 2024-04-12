<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\User\Domain\ValueObject\{UserId, UserFullName, UserDocumentNumber, UserEmail, UserPassword, UserIsMerchant, UserWalletAmount, UserCreatedAt, UserUpdatedAt};
use Src\Shared\Domain\Exception\BadRequestException;

class UserValueObjectTest extends TestCase{

    private UserId $id;
    private UserFullname $fullName;
    private UserDocumentNumber $documentNumber;
    private UserEmail $email;
    private UserPassword $password;
    private UserIsMerchant $isMerchant;
    private UserWalletAmount $walletAmount;
    private UserCreatedAt $createdAt;
    private UserUpdatedAt $updatedAt;

    public function setUp(): void{
    }

    public function testCreateIdWithNegative(){
        $this->expectException(BadRequestException::class);
        $this->id = new UserId(-10);
    }

    public function testCreateFullNameWithEmpty(){
        $this->expectException(BadRequestException::class);
        $this->fullName = new UserFullName('');
    }
    
    public function testCreateDocumentNumberWithEmpty(){
        $this->expectException(BadRequestException::class);
        $this->documentNumber = new UserDocumentNumber('');
    }
    
    public function testCreateDocumentNumberWithStringLengthTwo(){
        $this->expectException(BadRequestException::class);
        $this->documentNumber = new UserDocumentNumber('14');
    }
    
    public function testCreateDocumentNumberWithStringWithLetters(){
        $this->expectException(BadRequestException::class);
        $this->documentNumber = new UserDocumentNumber('abcdefgh');
    }

    public function testCreateEmailWithEmpty(){
        $this->expectException(BadRequestException::class);
        $this->email = new UserEmail('');
    }

    public function testCreateEmailWithIncompleteEmail1(){
        $this->expectException(BadRequestException::class);
        $this->email = new UserEmail('email@domain');
    }

    public function testCreateEmailWithIncompleteEmail2(){
        $this->expectException(BadRequestException::class);
        $this->email = new UserEmail('domain.com');
    }

    public function testCreateEmailWithIncompleteEmail3(){
        $this->expectException(BadRequestException::class);
        $this->email = new UserEmail('a@.ext');
    }

    public function testCreatePasswordWithEmpty(){
        $this->expectException(BadRequestException::class);
        $this->password = new UserPassword('');
    }

    public function testCreateWalletAmountWithNegative(){
        $this->expectException(BadRequestException::class);
        $this->walletAmount = new UserWalletAmount(-10);
    }
}