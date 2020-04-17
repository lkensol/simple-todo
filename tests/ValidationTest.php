<?php
require dirname(__DIR__).'/objects/validation.php';

use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    private $validation;

    protected function setUp(): void
    {
        $this->validation = new Validation();
    }

    protected function tearDown(): void
    {
        // code
    }

    /** @test */
    public function check_if_fields_not_empty() {
        $check = $this->validation->check_empty(array('username' =>'test', 'password'=>'test'), array('username', 'password'));
        $this->assertNull($check);
    }

    /** @test */
    public function check_if_fields_empty() {
        $check = $this->validation->check_empty(array('username' =>' ', 'password'=>' '), array('username', 'password'));
        $this->assertNotNull($check);
    }

    /** @test */
    public function check_name_len_ok() {
        $check = $this->validation->isNameValid('test');
        $this->assertTrue($check);
    }

    /** @test */
    public function check_name_len_not_ok() {
        $check = $this->validation->isNameValid('t');
        $this->assertNotTrue($check);
    }

    /** @test */
    public function check_pass_len_ok() {
        $check = $this->validation->isPasswordValid('test_password');
        $this->assertTrue($check);
    }

    /** @test */
    public function check_pass_len_not_ok() {
        $check = $this->validation->isPasswordValid('pwd');
        $this->assertNotTrue($check);
    }
}
