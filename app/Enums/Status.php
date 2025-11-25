<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'in-active';
    case ACCEPT = 'accept';

    case ACCEPTED = 'accepted';

    case DISABLED = 'disabled';

    case INITIATED = 'initiated';

    case BLOCKED = 'blocked';

    case DELETED = 'deleted';

    case SUCCESSFUL = 'successful';

    case FAILED = 'failed';

    case UNREAD = 'unread';

    case READ = 'read';

    case PENDING = 'pending';

    case UNKNOWN = 'unknown';

    case CANCELLED = 'cancelled';

    // case INACTIVE = 'inactive';

    case LOCKED = 'locked';

    case BANNED = 'banned';

    case BLOCK = 'block';

    case UNBLOCK = 'unblock';

    case OPEN = 'open';

    case DECLINED = 'declined';

    case SUSPENDED = 'suspended';

    case PUBLISH = 'published';
    case UNPUBLISH = 'unpublished';

    case DRAFT = 'draft';

    case EXPIRED = 'expired';
}
