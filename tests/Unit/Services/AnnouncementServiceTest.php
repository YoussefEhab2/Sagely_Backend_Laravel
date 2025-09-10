<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Services\AnnouncementService;
use App\Repositories\AnnouncementRepository;
use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnnouncementServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AnnouncementService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AnnouncementService(new AnnouncementRepository());
    }

    #[Test]
    public function it_creates_an_announcement()
    {
        $announcement = $this->service->create([
            'title' => 'Service Title',
            'content' => 'Service Content',
            'category' => 'News',
            'publishDate' => now(),
        ]);

        $this->assertDatabaseHas('announcement', ['title' => 'Service Title']);
        $this->assertInstanceOf(Announcement::class, $announcement);
    }

    #[Test]
    public function it_edits_an_announcement()
    {
        $announcement = Announcement::create([
            'title' => 'Old Title',
            'content' => 'Some content',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        $updated = $this->service->editAnnouncement($announcement->id, ['title' => 'New Title']);

        $this->assertEquals('New Title', $updated->title);
        $this->assertDatabaseHas('announcement', ['title' => 'New Title']);
    }

    #[Test]
    public function it_deletes_an_announcement()
    {
        $announcement = Announcement::create([
            'title' => 'To Delete',
            'content' => 'Delete content',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        $result = $this->service->deleteAnnouncement($announcement->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('announcement', ['id' => $announcement->id]);
    }

  #[Test]
    public function it_gets_all_announcements()
    {
        Announcement::create([
            'title' => 'First',
            'content' => 'First Content',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        Announcement::create([
            'title' => 'Second',
            'content' => 'Second Content',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        $all = $this->service->getAllAnnouncements();

        $this->assertCount(2, $all);
    }

    #[Test]
    public function it_gets_a_single_announcement()
    {
        $announcement = Announcement::create([
            'title' => 'Single',
            'content' => 'Single Content',
            'category' => 'News',
            'publishDate' => now(),
        ]);

        $found = $this->service->getAnnouncement($announcement->id);

        $this->assertEquals($announcement->id, $found->id);
    }
}
