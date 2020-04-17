<?php
require dirname(__DIR__).'/objects/project.php';

use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    private $project;

    protected function setUp(): void
    {
        $this->project = new Project();
    }

    protected function tearDown(): void
    {
        // code
    }

    /** @test */
    public function get_project() {
        $get = $this->project->get('1');
        $this->assertIsArray($get);
        $this->assertArrayHasKey('id_project', $get['0']);
        $this->assertArrayHasKey('name', $get['0']);
        $this->assertArrayHasKey('user_id', $get['0']);        
    }

    /** @test */
    public function update_project() {
        $update = $this->project->update('1', 'test job');
        $this->assertEquals('1', $update);
    }

    /** @test */
    public function delete_project() {
        $delete = $this->project->delete('6');
        $this->assertEquals('1', $delete);
    }


}