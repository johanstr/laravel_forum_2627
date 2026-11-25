<?php

use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;

test('thread page links to the page of each topic', function () {
    $author = User::factory()->create();
    $thread = Thread::factory()->for($author, 'author')->create();
    $topics = Topic::factory(2)->for($author, 'author')->for($thread)->create();

    $response = $this->get('/threads/'.$thread->id);

    foreach ($topics as $topic) {
        $response->assertSee('href="'.route('topics.show', ['id' => $topic->id]).'"', false);
    }
});
