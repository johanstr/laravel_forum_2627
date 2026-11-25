<?php

use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;

test('users can add a reply to a topic', function () {
    $user = User::factory()->create();
    $topic = Topic::factory()->for($user, 'author')->create();

    $response = $this->actingAs($user)->post('/topics/'.$topic->id.'/replies', [
        'content' => '<p>Mijn reply</p>',
    ]);

    $response->assertRedirect('/topics/'.$topic->id);
    $this->assertDatabaseHas('replies', [
        'content' => '<p>Mijn reply</p>',
        'user_id' => $user->id,
        'topic_id' => $topic->id,
    ]);
});

test('guests can not add a reply', function () {
    $topic = Topic::factory()->for(User::factory(), 'author')->create();

    $response = $this->post('/topics/'.$topic->id.'/replies', [
        'content' => '<p>Mijn reply</p>',
    ]);

    $response->assertRedirect('/login');
    $this->assertDatabaseEmpty('replies');
});

test('an empty reply is rejected', function () {
    $user = User::factory()->create();
    $topic = Topic::factory()->for($user, 'author')->create();

    $response = $this->actingAs($user)->post('/topics/'.$topic->id.'/replies', [
        'content' => '',
    ]);

    $response->assertSessionHasErrors(['content' => 'Een reply mag niet leeg zijn!']);
    $this->assertDatabaseEmpty('replies');
});

test('adding a reply returns 404 when the topic does not exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/topics/999/replies', [
        'content' => '<p>Mijn reply</p>',
    ]);

    $response->assertNotFound();
    $this->assertDatabaseEmpty('replies');
});

test('only logged in users see the reply form', function () {
    $user = User::factory()->create();
    $topic = Topic::factory()->for($user, 'author')->create();
    $form = 'action="'.route('replies.store', ['id' => $topic->id]).'"';

    $this->get('/topics/'.$topic->id)->assertDontSee($form, false)->assertSee('Meld je aan');
    $this->actingAs($user)->get('/topics/'.$topic->id)->assertSee($form, false);
});

test('html in a reply is shown as safe text', function (string $content) {
    $user = User::factory()->create();
    $topic = Topic::factory()->for($user, 'author')->create();
    Reply::factory()->for($topic)->for($user, 'author')->create(['content' => $content]);

    $response = $this->get('/topics/'.$topic->id);

    $response
        ->assertSee('Hallo')
        ->assertDontSee('<img src=x', false);
})->with([
    'html tag' => '<p>Hallo<img src=x onerror=alert(1)></p>',
    'encoded html tag' => '<p>Hallo &lt;img src=x onerror=alert(1)&gt;</p>',
]);
