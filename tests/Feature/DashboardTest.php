<?php

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('regular users are redirected to the landing page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertRedirect('/');
});

test('admins can view the dashboard', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertOk();
});

test('regular users end up on the landing page after login', function () {
    $user = User::factory()->create();

    $response = $this->followingRedirects()->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertViewIs('thread.index');
});

test('new users are not an admin by default', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($user->isAdmin())->toBeFalse();
});

test('only admins see the dashboard link', function (string $url) {
    $user = User::factory()->create();
    $admin = User::factory()->admin()->create();

    $this->actingAs($user)->get($url)->assertDontSee(route('dashboard'));
    $this->actingAs($admin)->get($url)->assertSee(route('dashboard'));
})->with(['/', '/profile']);

test('landing page contains a logout form for users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertSee('action="'.route('logout').'"', false);
});

test('dashboard shows the number of users, threads, topics and replies', function () {
    $admin = User::factory()->admin()->create();
    User::factory(2)->create();
    $thread = Thread::factory()->for($admin, 'author')->create();
    Thread::factory(2)->for($admin, 'author')->create();
    $topic = Topic::factory()->for($admin, 'author')->for($thread)->create();
    Topic::factory(3)->for($admin, 'author')->for($thread)->create();
    Reply::factory(6)->for($admin, 'author')->for($topic)->create();

    $response = $this->actingAs($admin)->get('/dashboard');

    $response
        ->assertViewHas('userCount', 3)
        ->assertViewHas('adminCount', 1)
        ->assertViewHas('threadCount', 3)
        ->assertViewHas('emptyThreadCount', 2)
        ->assertViewHas('topicCount', 4)
        ->assertViewHas('unansweredTopicCount', 3)
        ->assertViewHas('replyCount', 6)
        ->assertSee('gemiddeld 1,5 per topic');
});

test('dashboard lists only topics without replies and threads without topics', function () {
    $admin = User::factory()->admin()->create();
    $thread = Thread::factory()->for($admin, 'author')->create();
    $emptyThread = Thread::factory()->for($admin, 'author')->create();
    $answeredTopic = Topic::factory()->for($admin, 'author')->for($thread)->create();
    $unansweredTopic = Topic::factory()->for($admin, 'author')->for($thread)->create();
    Reply::factory()->for($admin, 'author')->for($answeredTopic)->create();

    $response = $this->actingAs($admin)->get('/dashboard');

    $response
        ->assertViewHas('unansweredTopics', fn ($topics) => $topics->modelKeys() === [$unansweredTopic->id])
        ->assertViewHas('emptyThreads', fn ($threads) => $threads->modelKeys() === [$emptyThread->id]);
});

test('dashboard shows the five latest replies, topics and users with the newest first', function () {
    $admin = User::factory()->admin()->create(['created_at' => now()->subDays(20)]);
    $thread = Thread::factory()->for($admin, 'author')->create();

    foreach (range(1, 6) as $day) {
        $user = User::factory()->create(['created_at' => now()->subDays($day)]);
        $topic = Topic::factory()->for($user, 'author')->for($thread)->create(['created_at' => now()->subDays($day)]);
        Reply::factory()->for($user, 'author')->for($topic)->create(['created_at' => now()->subDays($day)]);
    }

    $response = $this->actingAs($admin)->get('/dashboard');

    $response
        ->assertViewHas('latestReplies', fn ($replies) => $replies->modelKeys() === [1, 2, 3, 4, 5])
        ->assertViewHas('latestTopics', fn ($topics) => $topics->modelKeys() === [1, 2, 3, 4, 5])
        ->assertViewHas('latestUsers', fn ($users) => $users->modelKeys() === [2, 3, 4, 5, 6]);
});

test('dashboard works without threads, topics and replies', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertSee('Er zijn nog geen replies.');
});

test('dashboard shows html in a reply as safe text', function () {
    $admin = User::factory()->admin()->create();
    $topic = Topic::factory()->for($admin, 'author')->create();
    Reply::factory()->for($admin, 'author')->for($topic)->create(['content' => '<p>Hallo &lt;img src=x onerror=alert(1)&gt;</p>']);

    $response = $this->actingAs($admin)->get('/dashboard');

    $response
        ->assertSee('Hallo')
        ->assertDontSee('<img src=x', false);
});
