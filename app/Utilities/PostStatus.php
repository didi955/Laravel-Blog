<?php

declare(strict_types=1);

namespace App\Utilities;

enum PostStatus: string
{
    case DRAFT = 'Draft';
    case PUBLISHED = 'Published';
    case PENDING = 'Pending';
}
