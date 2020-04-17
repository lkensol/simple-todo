<?php
require dirname(__DIR__).'/objects/user.php';

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    protected function tearDown(): void
    {
        // code
    }

    /** @test */
    public function can_user_login()
    {
        $test_user = $this->user->login('admin', '123456');
        $this->assertTrue($test_user);
    }

    /** @test */
    public function trim_user_login()
    {
        $test_user = $this->user->login(' admin ', '123456');
        $this->assertTrue($test_user);
    }

    /** @test */
    public function trim_user_password()
    {
        $test_user = $this->user->login('admin', ' 123456 ');
        $this->assertTrue($test_user);
    }

    /** @test */
    public function can_user_register()
    {
        $reg_user = $this->user->register('test_user', '123456');
        $this->assertEquals('1', $reg_user);
    }
}
