<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Attachment;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_an_announcement()
    {
        $announcement = Announcement::create([
            'title' => 'Test Title',
            'content' => 'Test Content',
            'category' => 'General',
            'publishDate' => now(),
            'courseID' => null,
        ]);

        $this->assertDatabaseHas('announcement', [
            'title' => 'Test Title',
            'content' => 'Test Content',
        ]);
    }

    #[Test]
    public function it_belongs_to_a_course()
    {
        $course = Course::create([
            'name' => 'Test Course',
        ]);

        $announcement = Announcement::create([
            'title' => 'Linked Title',
            'content' => 'Linked Content',
            'category' => 'General',
            'publishDate' => now(),
            'courseID' => $course->id,
        ]);

        $this->assertNotNull($announcement->course);
        $this->assertEquals('Test Course', $announcement->course->name);
    }

    #[Test]
    public function it_can_have_attachments()
    {
        $announcement = Announcement::create([
            'title' => 'Attachment Test',
            'content' => 'Some Content',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        $attachment = Attachment::create([
            'announcementID' => $announcement->id,
            'filePath' => 'test.pdf',
        ]);

        $this->assertTrue($announcement->attachments->contains($attachment));

    }
}
