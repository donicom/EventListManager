CREATE TABLE IF NOT EXISTS `callback_query` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique identifier for this query',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Unique message identifier',
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Identifier of the message sent via the bot in inline mode, that originated the query',
  `chat_instance` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent',
  `data` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'Data associated with the callback button',
  `game_short_name` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'Short name of a Game to be returned, serves as the unique identifier for the game',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `message_id` (`message_id`),
  KEY `chat_id_2` (`chat_id`,`message_id`),
  CONSTRAINT `callback_query_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `callback_query_ibfk_2` FOREIGN KEY (`chat_id`, `message_id`) REFERENCES `message` (`chat_id`, `id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `chat` (
  `id` bigint(20) NOT NULL COMMENT 'Unique identifier for this chat',
  `type` enum('private','group','supergroup','channel') COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Type of chat, can be either private, group, supergroup or channel',
  `title` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT '' COMMENT 'Title, for supergroups, channels and group chats',
  `username` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Username, for private chats, supergroups and channels if available',
  `first_name` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'First name of the other party in a private chat',
  `last_name` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Last name of the other party in a private chat',
  `all_members_are_administrators` tinyint(1) DEFAULT 0 COMMENT 'True if a all members of this group are admins',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',
  `old_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier, this is filled when a group is converted to a supergroup',
  PRIMARY KEY (`id`),
  KEY `old_id` (`old_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `chosen_inline_result` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `result_id` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'The unique identifier for the result that was chosen',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'The user that chose the result',
  `location` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Sender location, only for bots that require user location',
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Identifier of the sent inline message',
  `query` text COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'The query that was used to obtain the result',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `chosen_inline_result_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `conversation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique user or chat identifier',
  `status` enum('active','cancelled','stopped') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'active' COMMENT 'Conversation state',
  `command` varchar(160) COLLATE utf8mb4_unicode_520_ci DEFAULT '' COMMENT 'Default command to execute',
  `notes` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Data stored from command',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `status` (`status`),
  CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `conversation_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `edited_message` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Unique message identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `edit_date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was edited in timestamp format',
  `text` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8',
  `entities` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text',
  `caption` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'For message with caption, the actual UTF-8 text of the caption',
  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),
  KEY `message_id` (`message_id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id_2` (`chat_id`,`message_id`),
  CONSTRAINT `edited_message_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  CONSTRAINT `edited_message_ibfk_2` FOREIGN KEY (`chat_id`, `message_id`) REFERENCES `message` (`chat_id`, `id`),
  CONSTRAINT `edited_message_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `inline_query` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique identifier for this query',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `location` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Location of the user',
  `query` text COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Text of the query',
  `offset` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Offset of the result',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `inline_query_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `message` (
  `chat_id` bigint(20) NOT NULL COMMENT 'Unique chat identifier',
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique message identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
  `date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was sent in timestamp format',
  `forward_from` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier, sender of the original message',
  `forward_from_chat` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier, chat the original message belongs to',
  `forward_from_message_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier of the original message in the channel',
  `forward_signature` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'For messages forwarded from channels, signature of the post author if present',
  `forward_sender_name` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Sender''s name for messages forwarded from users who disallow adding a link to their account in forwarded messages',
  `forward_date` timestamp NULL DEFAULT NULL COMMENT 'date the original message was sent in timestamp format',
  `reply_to_chat` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `reply_to_message` bigint(20) unsigned DEFAULT NULL COMMENT 'Message that this message is reply to',
  `edit_date` bigint(20) unsigned DEFAULT NULL COMMENT 'Date the message was last edited in Unix time',
  `media_group_id` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'The unique identifier of a media message group this message belongs to',
  `author_signature` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Signature of the post author for messages in channels',
  `text` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8mb4',
  `entities` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text',
  `caption_entities` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption',
  `audio` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Audio object. Message is an audio file, information about the file',
  `document` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Document object. Message is a general file, information about the file',
  `animation` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Message is an animation, information about the animation',
  `game` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Game object. Message is a game, information about the game',
  `photo` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Array of PhotoSize objects. Message is a photo, available sizes of the photo',
  `sticker` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Sticker object. Message is a sticker, information about the sticker',
  `video` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Video object. Message is a video, information about the video',
  `voice` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Voice Object. Message is a Voice, information about the Voice',
  `video_note` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'VoiceNote Object. Message is a Video Note, information about the Video Note',
  `caption` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'For message with caption, the actual UTF-8 text of the caption',
  `contact` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Contact object. Message is a shared contact, information about the contact',
  `location` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Location object. Message is a shared location, information about the location',
  `venue` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Venue object. Message is a Venue, information about the Venue',
  `poll` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Poll object. Message is a native poll, information about the poll',
  `new_chat_members` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'List of unique user identifiers, new member(s) were added to the group, information about them (one of these members may be the bot itself)',
  `left_chat_member` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier, a member was removed from the group, information about them (this member may be the bot itself)',
  `new_chat_title` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'A chat title was changed to this value',
  `new_chat_photo` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Array of PhotoSize objects. A chat photo was change to this value',
  `delete_chat_photo` tinyint(1) DEFAULT 0 COMMENT 'Informs that the chat photo was deleted',
  `group_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group has been created',
  `supergroup_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the supergroup has been created',
  `channel_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the channel chat has been created',
  `migrate_to_chat_id` bigint(20) DEFAULT NULL COMMENT 'Migrate to chat identifier. The group has been migrated to a supergroup with the specified identifier',
  `migrate_from_chat_id` bigint(20) DEFAULT NULL COMMENT 'Migrate from chat identifier. The supergroup has been migrated from a group with the specified identifier',
  `pinned_message` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Message object. Specified message was pinned',
  `invoice` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Message is an invoice for a payment, information about the invoice',
  `successful_payment` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Message is a service message about a successful payment, information about the payment',
  `connected_website` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'The domain name of the website on which the user has logged in.',
  `passport_data` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Telegram Passport data',
  `reply_markup` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Inline keyboard attached to the message',
  PRIMARY KEY (`chat_id`,`id`),
  KEY `user_id` (`user_id`),
  KEY `forward_from` (`forward_from`),
  KEY `forward_from_chat` (`forward_from_chat`),
  KEY `reply_to_chat` (`reply_to_chat`),
  KEY `reply_to_message` (`reply_to_message`),
  KEY `left_chat_member` (`left_chat_member`),
  KEY `migrate_from_chat_id` (`migrate_from_chat_id`),
  KEY `migrate_to_chat_id` (`migrate_to_chat_id`),
  KEY `reply_to_chat_2` (`reply_to_chat`,`reply_to_message`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  CONSTRAINT `message_ibfk_3` FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  CONSTRAINT `message_ibfk_4` FOREIGN KEY (`forward_from_chat`) REFERENCES `chat` (`id`),
  CONSTRAINT `message_ibfk_5` FOREIGN KEY (`reply_to_chat`, `reply_to_message`) REFERENCES `message` (`chat_id`, `id`),
  CONSTRAINT `message_ibfk_6` FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  CONSTRAINT `message_ibfk_7` FOREIGN KEY (`left_chat_member`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `my_events` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `chat_id` bigint(20) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  `name` varchar(50) DEFAULT '',
  `description` varchar(1000) DEFAULT '',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_EVENT_CHAT` (`chat_id`),
  CONSTRAINT `FK_EVENT_CHAT` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `my_last_input_command` (
  `chat_id` bigint(20) NOT NULL,
  `command` text DEFAULT '0',
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `my_participants` (
  `user_id` bigint(20) NOT NULL,
  `event_id` bigint(20) NOT NULL,
  PRIMARY KEY (`user_id`,`event_id`),
  KEY `FK_PARTICIPANT_EVENT` (`event_id`),
  CONSTRAINT `FK_PARTICIPANT_EVENT` FOREIGN KEY (`event_id`) REFERENCES `my_events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_PARTICIPANT_USER` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `poll` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique poll identifier',
  `question` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'Poll question',
  `options` text COLLATE utf8mb4_unicode_520_ci NOT NULL COMMENT 'List of poll options',
  `is_closed` tinyint(1) DEFAULT 0 COMMENT 'True, if the poll is closed',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `pre_checkout_query` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique query identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'User who sent the query',
  `currency` char(3) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Three-letter ISO 4217 currency code',
  `total_amount` bigint(20) DEFAULT NULL COMMENT 'Total price in the smallest units of the currency',
  `invoice_payload` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'Bot specified invoice payload',
  `shipping_option_id` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Identifier of the shipping option chosen by the user',
  `order_info` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Order info provided by the user',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pre_checkout_query_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `request_limiter` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Unique chat identifier',
  `inline_message_id` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Identifier of the sent inline message',
  `method` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Request method',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `shipping_query` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Unique query identifier',
  `user_id` bigint(20) DEFAULT NULL COMMENT 'User who sent the query',
  `invoice_payload` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'Bot specified invoice payload',
  `shipping_address` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'User specified shipping address',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `shipping_query_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `telegram_update` (
  `id` bigint(20) unsigned NOT NULL COMMENT 'Update''s unique identifier',
  `chat_id` bigint(20) DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New incoming message of any kind - text, photo, sticker, etc.',
  `edited_message_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New version of a message that is known to the bot and was edited',
  `channel_post_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New incoming channel post of any kind - text, photo, sticker, etc.',
  `edited_channel_post_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New version of a channel post that is known to the bot and was edited',
  `inline_query_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New incoming inline query',
  `chosen_inline_result_id` bigint(20) unsigned DEFAULT NULL COMMENT 'The result of an inline query that was chosen by a user and sent to their chat partner',
  `callback_query_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New incoming callback query',
  `shipping_query_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New incoming shipping query. Only for invoices with flexible price',
  `pre_checkout_query_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New incoming pre-checkout query. Contains full information about checkout',
  `poll_id` bigint(20) unsigned DEFAULT NULL COMMENT 'New poll state. Bots receive only updates about polls, which are sent or stopped by the bot',
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  KEY `chat_message_id` (`chat_id`,`message_id`),
  KEY `edited_message_id` (`edited_message_id`),
  KEY `channel_post_id` (`channel_post_id`),
  KEY `edited_channel_post_id` (`edited_channel_post_id`),
  KEY `inline_query_id` (`inline_query_id`),
  KEY `chosen_inline_result_id` (`chosen_inline_result_id`),
  KEY `callback_query_id` (`callback_query_id`),
  KEY `shipping_query_id` (`shipping_query_id`),
  KEY `pre_checkout_query_id` (`pre_checkout_query_id`),
  KEY `poll_id` (`poll_id`),
  KEY `chat_id` (`chat_id`,`channel_post_id`),
  CONSTRAINT `telegram_update_ibfk_1` FOREIGN KEY (`chat_id`, `message_id`) REFERENCES `message` (`chat_id`, `id`),
  CONSTRAINT `telegram_update_ibfk_10` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`),
  CONSTRAINT `telegram_update_ibfk_2` FOREIGN KEY (`edited_message_id`) REFERENCES `edited_message` (`id`),
  CONSTRAINT `telegram_update_ibfk_3` FOREIGN KEY (`chat_id`, `channel_post_id`) REFERENCES `message` (`chat_id`, `id`),
  CONSTRAINT `telegram_update_ibfk_4` FOREIGN KEY (`edited_channel_post_id`) REFERENCES `edited_message` (`id`),
  CONSTRAINT `telegram_update_ibfk_5` FOREIGN KEY (`inline_query_id`) REFERENCES `inline_query` (`id`),
  CONSTRAINT `telegram_update_ibfk_6` FOREIGN KEY (`chosen_inline_result_id`) REFERENCES `chosen_inline_result` (`id`),
  CONSTRAINT `telegram_update_ibfk_7` FOREIGN KEY (`callback_query_id`) REFERENCES `callback_query` (`id`),
  CONSTRAINT `telegram_update_ibfk_8` FOREIGN KEY (`shipping_query_id`) REFERENCES `shipping_query` (`id`),
  CONSTRAINT `telegram_update_ibfk_9` FOREIGN KEY (`pre_checkout_query_id`) REFERENCES `pre_checkout_query` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL COMMENT 'Unique identifier for this user or bot',
  `is_bot` tinyint(1) DEFAULT 0 COMMENT 'True, if this user is a bot',
  `first_name` char(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'User''s or bot''s first name',
  `last_name` char(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'User''s or bot''s last name',
  `username` char(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'User''s or bot''s username',
  `language_code` char(10) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'IETF language tag of the user''s language',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `user_chat` (
  `user_id` bigint(20) NOT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint(20) NOT NULL COMMENT 'Unique user or chat identifier',
  PRIMARY KEY (`user_id`,`chat_id`),
  KEY `chat_id` (`chat_id`),
  CONSTRAINT `user_chat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_chat_ibfk_2` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

