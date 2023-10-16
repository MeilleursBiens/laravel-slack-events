# Slack Events API for Laravel 
[![Latest Stable Version](https://poser.pugx.org/meilleursbiens/laravel-slack-events/v/stable)](https://packagist.org/packages/meilleursbiens/laravel-slack-events)
[![License](https://poser.pugx.org/meilleursbiens/laravel-slack-events/license)](https://packagist.org/packages/meilleursbiens/laravel-slack-events)

*Work with Slack Events API as easily as with native Laravel 10 events and event listeners.*

### Reasons to use this package for the [Slack Events API](https://api.slack.com/events-api)
* Based on native Laravel Events
* Supports all Slack Event types (including events for new Workspace Apps!)
* Supports token validation
* Supports URL verification and "challenge" requests
* PSR compatible code
* Full documentation
* Almost full test coverage
* Lots of emoji in the documentation (even cats! :cat2:)

## Installation
**1)** Require the package with Composer
```bash
composer require meilleursbiens/laravel-slack-events
```
**2)** If you're using Laravel version 5.5 or higher, the SlackEventsServiceProvider will be automatically discovered. Get started fast!

**3)** If you're using a version of Laravel lower than 5.5, you'll still have to register the service provider manually. Open `config/app.php` and add `\MeilleursBiens\LaravelSlackEvents\SlackEventsServiceProvider::class` to the `providers[]` array.

*For example:*
```php
// ...

'providers' => [
// ...
// A whole bunch of providers
// ...

\MeilleursBiens\LaravelSlackEvents\SlackEventsServiceProvider::class,
],

// ...
```
If you are using Laravel 5.3 or higher, you will find a comment in `config/app.php` with text like `/* Package Service Providers... */`, which can help you find the right place.

**4)** Publish the config file
```bash
php artisan vendor:publish

```

Choose the option for the Service Provider, or find `slack-events` in the `Tag:` list at the bottom.

Next we'll configure some settings to use the [Slack API](https://api.slack.com).

**5)** Create an App or Open an Existing One
If you want to work with Slack's new _Workspace Apps_, read over the information in the [Developer Preview](https://api.slack.com/workspace-apps-preview) for Workspace Apps, and create a new _workspace token_ App to get started.

If you're not using a Workspace App, open the [Slack Apps](https://api.slack.com/apps) page and create a new _user token_ App.

If you want to modify an existing app to use with Slack-Events, you can find both _Workspace Token_ Apps and _User Token_ Apps on the [Your Apps](https://api.slack.com/apps) page.

**6)** Once you've created or opened your app you should see the *Basic Information* page. Scroll down to the "App Credentials" section, and copy the *Verification Token* at the bottom.

<img src="https://cloud.githubusercontent.com/assets/8103985/17901937/ebdbdb3e-696d-11e6-96b4-b0794d74ed9a.png" alt="verification_token" style="height: 250px; width: auto;">

Open `.env` and paste your Verification Token under the `'SLACK_EVENT_TOKEN'` key:
```php
SLACK_EVENT_TOKEN=your-token
```

**7)** Now open the "Event Subscriptions" page. Here you must enable events, add events you wish to listen for and set **Request URL**. Request URL is the `'route'` key in your `config/slackEvents.php` file:
```php
return [
    /*
    |-------------------------------------------------------------
    | Your validation token from "App Credentials"
    |-------------------------------------------------------------
    */
    'token' => env('SLACK_EVENT_TOKEN', 'your-validation-token-here'),

    /*
    |-------------------------------------------------------------
    | Events Request URL — path, where events will be served
    |-------------------------------------------------------------
    */
    'route' => '/api/slack/event/fire', // <===== THIS IS YOUR REQUEST URL
];
```
`'route'` works just like built-in Laravel routes, so if your site URL is `https://example.com` and your `'route'` is `/api/slack/event/fire`, then your full Request URL is `https://example.com/api/slack/event/fire`. You can leave it as-is or set your own route instead of the default `/api/slack/event/fire`.

This package will do all verification and "challenge" work for you, so you only need to set your Request URL — by default it's:
```
https://[your-site-url]/api/slack/event/fire
```
![request_url](https://cloud.githubusercontent.com/assets/8103985/17905448/b8ed582a-697b-11e6-890d-e0c1bcff0bd7.png)

## Usage

**Also see: [Quick Example](#example)**.

Thanks to this package, working with [Slack Events](https://api.slack.com/events-api) is the same as working with [Laravel Events](https://laravel.com/docs/master/events). So if you haven't read the [Laravel Events documentation](https://laravel.com/docs/master/events) or [Slack Events API documentation](https://api.slack.com/events-api) yet, it's **highly recommended** to read them before you start.

This package provides a separate Laravel Event class for [every Slack event](https://api.slack.com/events) that has Slack Events API support. For example, the `reaction_added` event implementation is the `MeilleursBiens\LaravelSlackEvents\Events\ReactionAdded` class.

**Also see: [Full list of supported events and their classes](#cherries-full-list-of-supported-events-and-their-classes).**

Each Event class has public fields representing the real Slack Event request:

| Field                 | Description                                                               |
|-----------------------|---------------------------------------------------------------------------|
| public $token;        | Verification token                                                        |
| public $team_id;      | The unique identifier for the team where this event occurred.             |
| public $api_app_id    | The unique identifier for the application this event is intended for.     |
| public $event;        | Contains the inner set of fields representing the event that's happening. |
| public $data          | Alias for `public $event` field. Makes code more clear if your event object name is `$event` too, so you can write `$event->data` instead of `$event->event`.                                                |
| public $type;         | This reflects the type of callback you're receiving.                      |
| public $authed_users; | An array of string-based User IDs.                                        |

For example, if you want to get the reaction name from [reaction_added](https://api.slack.com/events/reaction_added) event, you can get it from the `ReactionAdded` event class like this:
```php
$reactionAdded->event['reaction']; // reaction name, something like :thumbsup:
```

So, suppose we want to make a `reaction_added` Slack Event listener. What do we need to do?

### Example

**1)** Open the `App/Listeners` directory (or create it if it doesn't exist). Now create a new file and call it `ReactionAddedListener.php`. Paste in this code:
```php
<?php

namespace App\Listeners;

use \MeilleursBiens\LaravelSlackEvents\Events\ReactionAdded;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReactionAddedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // Here you can setup something
    }

    /**
     * Handle the event.
     *
     * @param  ReactionAdded  $event
     * @return void
     */
    public function handle(ReactionAdded $reactionAdded)
    {
        // Do some magic with event data
        Log::info('New reaction added, reaction name is: ' . $reactionAdded->event['reaction']);
    }
}

```
As you can see, it's a normal event listener. You might notice that the listener `implements ShouldQueue` — it's useful because our app must respond to the request **within three seconds** in order to adhere to the Slack Events API terms. If you want some long-running stuff in your event listener, you need to configure a [queue](https://laravel.com/docs/5.2/queues).

**2)** Now we add this listener to `/App/Providers/EventServiceProvider.php` like any other event listener:
```php
// ...

protected $listen = [
        
        // ...

        \MeilleursBiens\LaravelSlackEvents\Events\ReactionAdded::class => [
            \App\Listeners\ReactionAddedListener::class
        ]
    ];
    
// ...
```

**3)** Profit! You are ready to go.

## :cherries: Full list of supported events and their classes

| [Event Name](https://api.slack.com/events) | [Event Class](https://github.com/MeilleursBiens/Slack-Events/tree/master/src/Events)                    |
|------------|:-------------------------------------------------:|
|channel_archive|\MeilleursBiens\LaravelSlackEvents\Events\ChannelArchive|
|channel_created|\MeilleursBiens\LaravelSlackEvents\Events\ChannelCreated|
|channel_deleted|\MeilleursBiens\LaravelSlackEvents\Events\ChannelDeleted|
|channel_history_changed|\MeilleursBiens\LaravelSlackEvents\Events\ChannelHistoryChanged|
|channel_joined|\MeilleursBiens\LaravelSlackEvents\Events\ChannelJoined|
|channel_rename|\MeilleursBiens\LaravelSlackEvents\Events\ChannelRename|
|channel_unarchive|\MeilleursBiens\LaravelSlackEvents\Events\ChannelUnarchive|
|dnd_updated|\MeilleursBiens\LaravelSlackEvents\Events\DndUpdated|
|dnd_updated_user|\MeilleursBiens\LaravelSlackEvents\Events\DndUpdatedUser|
|email_domain_changed|\MeilleursBiens\LaravelSlackEvents\Events\EmailDomainChanged|
|emoji_changed|\MeilleursBiens\LaravelSlackEvents\Events\EmojiChanged|
|file_change|\MeilleursBiens\LaravelSlackEvents\Events\FileChange|
|file_comment_added|\MeilleursBiens\LaravelSlackEvents\Events\FileCommentAdded|
|file_comment_deleted|\MeilleursBiens\LaravelSlackEvents\Events\FileCommentDeleted|
|file_comment_edited|\MeilleursBiens\LaravelSlackEvents\Events\FileCommentEdited|
|file_created|\MeilleursBiens\LaravelSlackEvents\Events\FileCreated|
|file_deleted|\MeilleursBiens\LaravelSlackEvents\Events\FileDeleted|
|file_public|\MeilleursBiens\LaravelSlackEvents\Events\FilePublic|
|file_shared|\MeilleursBiens\LaravelSlackEvents\Events\FileShared|
|file_unshared|\MeilleursBiens\LaravelSlackEvents\Events\FileUnshared|
|group_archive|\MeilleursBiens\LaravelSlackEvents\Events\GroupArchive|
|group_close|\MeilleursBiens\LaravelSlackEvents\Events\GroupClose|
|group_history_changed|\MeilleursBiens\LaravelSlackEvents\Events\GroupHistoryChanged|
|group_open|\MeilleursBiens\LaravelSlackEvents\Events\GroupOpen|
|group_rename|\MeilleursBiens\LaravelSlackEvents\Events\GroupRename|
|group_unarchive|\MeilleursBiens\LaravelSlackEvents\Events\GroupUnarchive|
|im_close|\MeilleursBiens\LaravelSlackEvents\Events\ImClose|
|im_created|\MeilleursBiens\LaravelSlackEvents\Events\ImCreated|
|im_history_changed|\MeilleursBiens\LaravelSlackEvents\Events\ImHistoryChanged|
|im_open|\MeilleursBiens\LaravelSlackEvents\Events\ImOpen|
|link_shared|\MeilleursBiens\LaravelSlackEvents\Events\LinkShared|
|message|\MeilleursBiens\LaravelSlackEvents\Events\Message|
|message.channels|\MeilleursBiens\LaravelSlackEvents\Events\MessageChannels|
|message.groups|\MeilleursBiens\LaravelSlackEvents\Events\MessageGroups|
|message.im|\MeilleursBiens\LaravelSlackEvents\Events\MessageIm|
|message.mpim|\MeilleursBiens\LaravelSlackEvents\Events\MessageMpim|
|pin_added|\MeilleursBiens\LaravelSlackEvents\Events\PinAdded|
|pin_removed|\MeilleursBiens\LaravelSlackEvents\Events\PinRemoved|
|reaction_added|\MeilleursBiens\LaravelSlackEvents\Events\ReactionAdded|
|reaction_removed|\MeilleursBiens\LaravelSlackEvents\Events\ReactionRemoved|
|star_added|\MeilleursBiens\LaravelSlackEvents\Events\StarAdded|
|star_removed|\MeilleursBiens\LaravelSlackEvents\Events\StarRemoved|
|subteam_created|\MeilleursBiens\LaravelSlackEvents\Events\SubteamCreated|
|subteam_self_added|\MeilleursBiens\LaravelSlackEvents\Events\SubteamSelfAdded|
|subteam_self_removed|\MeilleursBiens\LaravelSlackEvents\Events\SubteamSelfRemoved|
|subteam_updated|\MeilleursBiens\LaravelSlackEvents\Events\SubteamUpdated|
|team_domain_change|\MeilleursBiens\LaravelSlackEvents\Events\TeamDomainChange|
|team_join|\MeilleursBiens\LaravelSlackEvents\Events\TeamJoin|
|team_rename|\MeilleursBiens\LaravelSlackEvents\Events\TeamRename|
|url_verification|\MeilleursBiens\LaravelSlackEvents\Events\UrlVerification|
|user_change|\MeilleursBiens\LaravelSlackEvents\Events\UserChange|

## :hibiscus: Contributing

Feel free to star this repository, create pull requests or issues, and report typos.

## :books: Reference
* Read the [Slack Events API official documentation](https://api.slack.com/events-api)
* Read the [Laravel Events documentation](https://laravel.com/docs/master/events)
* To make Slack Web API calls we recommend the [Laravel Slack API package](https://github.com/MeilleursBiens/Laravel-Slack-Web-API)
