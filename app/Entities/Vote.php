<?php

namespace App\Entities;

use Carbon\Carbon;

class Vote extends Entity
{
    public function __construct(
        public readonly Candidate $candidate,
        public readonly Voter $voter,
        public readonly Carbon $votedAt = new Carbon('now')
    ) {
    }
}
