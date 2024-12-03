<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Sources extends Enum
{
    const Guardian = 'Guardian';
    const NewYorkTimes = 'New York Times';
    const NewsAPI = 'News API';
}
