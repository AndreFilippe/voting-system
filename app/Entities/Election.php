<?php

namespace App\Entities;

use Carbon\Carbon;

class Election extends Entity
{
    private array $candidates = [];

    public function __construct(
        public readonly string $description,
        private Carbon $startAt,
        private Carbon $endAt,
        public bool $enabled,
        private array $votes = [],
        array $candidates = [],
    ) {
        $this->setCandidates($candidates);
    }

    private function setCandidates(array $candidates): void
    {
        $this->checkCandidates($candidates);
        $this->candidates = $candidates;
    }

    private function checkCandidates(array $candidates): void
    {
        foreach ($candidates as $candidate) {
            if (!$candidate instanceof Candidate) throw new \Exception("Candidates invalid", 400);
        }
    }
}
