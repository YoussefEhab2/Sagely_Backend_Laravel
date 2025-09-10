<?php

namespace Tests\Unit\Repositories;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Announcement;
use App\Repositories\AnnouncementRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnnouncementRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected AnnouncementRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new AnnouncementRepository();
    }

    #[Test]
    public function it_can_create_an_announcement()
    {
        $announcement = $this->repository->create([
            'title' => 'Repo Test',
            'content' => 'Repo content',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        $this->assertDatabaseHas('announcement', ['title' => 'Repo Test']);
    }

    #[Test]
    public function it_can_find_by_id()
    {
        $announcement = Announcement::create([
            'title' => 'Find Me',
            'content' => 'Find Content',
            'category' => 'News',
            'publishDate' => now(),
        ]);

        $found = $this->repository->findById($announcement->id);

        $this->assertEquals($announcement->id, $found->id);
    }

    #[Test]
    public function it_can_update_an_announcement()
    {
        $announcement = Announcement::create([
            'title' => 'Old Title',
            'content' => 'Old Content',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        $this->repository->update($announcement, ['title' => 'Updated Title']);

        $this->assertDatabaseHas('announcement', ['title' => 'Updated Title']);
    }

    #[Test]
    public function it_can_delete_an_announcement()
    {
        $announcement = Announcement::create([
            'title' => 'Delete Me',
            'content' => 'To be deleted',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        $result = $this->repository->delete($announcement->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('announcement', ['id' => $announcement->id]);
    }

   #[Test]
    public function it_can_get_all_announcements()
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

        Announcement::create([
            'title' => 'Third',
            'content' => 'Third Content',
            'category' => 'General',
            'publishDate' => now(),
        ]);

        $all = $this->repository->getAll();

        $this->assertCount(3, $all);
    }
}
