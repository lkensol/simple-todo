<?php
require dirname(__DIR__).'/objects/task.php';

use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $task;

    protected function setUp(): void
    {
        $this->task = new Task();
    }

    protected function tearDown(): void
    {
        // code
    }

    /** @test */
    public function get_task() {
        $get = $this->task->get('1', '1');
        $this->assertIsArray($get);
        $this->assertArrayHasKey('id_task', $get['0']);
        $this->assertArrayHasKey('name', $get['0']);
        $this->assertArrayHasKey('status', $get['0']);
        $this->assertArrayHasKey('project_id', $get['0']);
        $this->assertArrayHasKey('user_id', $get['0']);
        $this->assertArrayHasKey('order_task', $get['0']);        
    }

    /** @test */
    public function update_task() {
        $update = $this->task->update('17', 'job', '1');
        $this->assertEquals('1', $update);
    }

    /** @test */
    public function delete_task() {
        $delete = $this->task->delete('26');
        $this->assertEquals('1', $delete);
    }

    /** @test */
    public function save_order() {
        $save = $this->task->save_order('17,3,4,14');
        $this->assertTrue($save);
    }
}