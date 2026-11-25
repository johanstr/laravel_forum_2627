<?php

use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;

test('topic page shows the topic with its author', function () {
    $author = User::factory()->create();
    $topic = Topic::factory()->for($author, 'author')->create();

    $response = $this->get('/topics/'.$topic->id);

    $response
        ->assertViewIs('topic.show')
        ->assertSee('Topic - '.$topic->title)
        ->assertSee($topic->content)
        ->assertSee($author->name);
});

test('topic page returns 404 when the topic does not exist', function () {
    $response = $this->get('/topics/999');

    $response->assertNotFound();
});

test('topic page shows only the replies of that topic with their author', function () {
    $topic = Topic::factory()->for(User::factory(), 'author')->create();
    $otherTopic = Topic::factory()->for(User::factory(), 'author')->create();
    $replyAuthor = User::factory()->create();
    Reply::factory()->for($topic)->for($replyAuthor, 'author')->create(['content' => 'Reply op deze topic']);
    Reply::factory()->for($otherTopic)->for($replyAuthor, 'author')->create(['content' => 'Reply op een andere topic']);

    $response = $this->get('/topics/'.$topic->id);

    $response
        ->assertSee('Reply op deze topic')
        ->assertSee($replyAuthor->name)
        ->assertDontSee('Reply op een andere topic');
});

test('topic page shows a message when there are no replies', function () {
    $topic = Topic::factory()->for(User::factory(), 'author')->create();

    $response = $this->get('/topics/'.$topic->id);

    $response->assertSee('Er zijn nog geen replies op deze topic.');
});
