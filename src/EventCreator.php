<?php

namespace MeilleursBiens\LaravelSlackEvents;

use MeilleursBiens\LaravelSlackEvents\Events\Base\SlackEvent;
use MeilleursBiens\LaravelSlackEvents\Events\ChannelArchive;
use MeilleursBiens\LaravelSlackEvents\Events\ChannelCreated;
use MeilleursBiens\LaravelSlackEvents\Events\ChannelDeleted;
use MeilleursBiens\LaravelSlackEvents\Events\ChannelHistoryChanged;
use MeilleursBiens\LaravelSlackEvents\Events\ChannelJoined;
use MeilleursBiens\LaravelSlackEvents\Events\ChannelRename;
use MeilleursBiens\LaravelSlackEvents\Events\ChannelUnarchive;
use MeilleursBiens\LaravelSlackEvents\Events\DndUpdated;
use MeilleursBiens\LaravelSlackEvents\Events\DndUpdatedUser;
use MeilleursBiens\LaravelSlackEvents\Events\EmailDomainChanged;
use MeilleursBiens\LaravelSlackEvents\Events\EmojiChanged;
use MeilleursBiens\LaravelSlackEvents\Events\FileChange;
use MeilleursBiens\LaravelSlackEvents\Events\FileCommentAdded;
use MeilleursBiens\LaravelSlackEvents\Events\FileCommentDeleted;
use MeilleursBiens\LaravelSlackEvents\Events\FileCommentEdited;
use MeilleursBiens\LaravelSlackEvents\Events\FileCreated;
use MeilleursBiens\LaravelSlackEvents\Events\FileDeleted;
use MeilleursBiens\LaravelSlackEvents\Events\FilePublic;
use MeilleursBiens\LaravelSlackEvents\Events\FileShared;
use MeilleursBiens\LaravelSlackEvents\Events\FileUnshared;
use MeilleursBiens\LaravelSlackEvents\Events\GroupArchive;
use MeilleursBiens\LaravelSlackEvents\Events\GroupClose;
use MeilleursBiens\LaravelSlackEvents\Events\GroupHistoryChanged;
use MeilleursBiens\LaravelSlackEvents\Events\GroupOpen;
use MeilleursBiens\LaravelSlackEvents\Events\GroupRename;
use MeilleursBiens\LaravelSlackEvents\Events\GroupUnarchive;
use MeilleursBiens\LaravelSlackEvents\Events\ImClose;
use MeilleursBiens\LaravelSlackEvents\Events\ImCreated;
use MeilleursBiens\LaravelSlackEvents\Events\ImHistoryChanged;
use MeilleursBiens\LaravelSlackEvents\Events\ImOpen;
use MeilleursBiens\LaravelSlackEvents\Events\LinkShared;
use MeilleursBiens\LaravelSlackEvents\Events\Message;
use MeilleursBiens\LaravelSlackEvents\Events\MessageChannels;
use MeilleursBiens\LaravelSlackEvents\Events\MessageGroups;
use MeilleursBiens\LaravelSlackEvents\Events\MessageIm;
use MeilleursBiens\LaravelSlackEvents\Events\MessageMpim;
use MeilleursBiens\LaravelSlackEvents\Events\PinAdded;
use MeilleursBiens\LaravelSlackEvents\Events\PinRemoved;
use MeilleursBiens\LaravelSlackEvents\Events\ReactionAdded;
use MeilleursBiens\LaravelSlackEvents\Events\ReactionRemoved;
use MeilleursBiens\LaravelSlackEvents\Events\StarAdded;
use MeilleursBiens\LaravelSlackEvents\Events\StarRemoved;
use MeilleursBiens\LaravelSlackEvents\Events\SubteamCreated;
use MeilleursBiens\LaravelSlackEvents\Events\SubteamSelfAdded;
use MeilleursBiens\LaravelSlackEvents\Events\SubteamSelfRemoved;
use MeilleursBiens\LaravelSlackEvents\Events\SubteamUpdated;
use MeilleursBiens\LaravelSlackEvents\Events\TeamDomainChange;
use MeilleursBiens\LaravelSlackEvents\Events\TeamJoin;
use MeilleursBiens\LaravelSlackEvents\Events\TeamRename;
use MeilleursBiens\LaravelSlackEvents\Events\UrlVerification;
use MeilleursBiens\LaravelSlackEvents\Events\UserChange;

/**
 * Event factory
 *
 * @package MeilleursBiens\LaravelSlackEvents
 */
class EventCreator
{
    /**
     * @var array event type to event class mapping
     */
    public $map = [
        'channel_archive' => ChannelArchive::class,
        'channel_created' => ChannelCreated::class,
        'channel_deleted' => ChannelDeleted::class,
        'channel_history_changed' => ChannelHistoryChanged::class,
        'channel_joined' => ChannelJoined::class,
        'channel_rename' => ChannelRename::class,
        'channel_unarchive' => ChannelUnarchive::class,
        'dnd_updated' => DndUpdated::class,
        'dnd_updated_user' => DndUpdatedUser::class,
        'email_domain_changed' => EmailDomainChanged::class,
        'emoji_changed' => EmojiChanged::class,
        'file_change' => FileChange::class,
        'file_comment_added' => FileCommentAdded::class,
        'file_comment_deleted' => FileCommentDeleted::class,
        'file_comment_edited' => FileCommentEdited::class,
        'file_created' => FileCreated::class,
        'file_deleted' => FileDeleted::class,
        'file_public' => FilePublic::class,
        'file_shared' => FileShared::class,
        'file_unshared' => FileUnshared::class,
        'group_archive' => GroupArchive::class,
        'group_close' => GroupClose::class,
        'group_history_changed' => GroupHistoryChanged::class,
        'group_open' => GroupOpen::class,
        'group_rename' => GroupRename::class,
        'group_unarchive' => GroupUnarchive::class,
        'im_close' => ImClose::class,
        'im_created' => ImCreated::class,
        'im_history_changed' => ImHistoryChanged::class,
        'im_open' => ImOpen::class,
        'link_shared' => LinkShared::class,
        'message' => Message::class,
        'message.channels' => MessageChannels::class,
        'message.groups' => MessageGroups::class,
        'message.im' => MessageIm::class,
        'message.mpim' => MessageMpim::class,
        'pin_added' => PinAdded::class,
        'pin_removed' => PinRemoved::class,
        'reaction_added' => ReactionAdded::class,
        'reaction_removed' => ReactionRemoved::class,
        'star_added' => StarAdded::class,
        'star_removed' => StarRemoved::class,
        'subteam_created' => SubteamCreated::class,
        'subteam_self_added' => SubteamSelfAdded::class,
        'subteam_self_removed' => SubteamSelfRemoved::class,
        'subteam_updated' => SubteamUpdated::class,
        'team_domain_change' => TeamDomainChange::class,
        'team_join' => TeamJoin::class,
        'team_rename' => TeamRename::class,
        'url_verification' => UrlVerification::class,
        'user_change' => UserChange::class,
    ];

    /**
     * Returns new event instance
     *
     * @param $eventType
     * @return SlackEvent
     */
    public function make($eventType)
    {
        return new $this->map[$eventType];
    }
}
