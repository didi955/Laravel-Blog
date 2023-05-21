<?php

namespace App\Utilities;

enum PostStatus: string
{
    case DRAFT = 'Draft';
    case PUBLISHED = 'Published';
    case PENDING = 'Pending';
}
