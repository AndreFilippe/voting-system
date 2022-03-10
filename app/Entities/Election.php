<?php

namespace App\Entities;

use Carbon\Carbon;

class Election extends Entity
{
    private array $candidates = [];
    private array $votes = [];

    public function __construct(
        public readonly string $description,
        private Carbon $startAt,
        private Carbon $endAt,
        public bool $enabled,
        array $votes = [],
        array $candidates = [],
    ) {
        $this->setCandidates($candidates);
        $this->setVotes($votes);
    }

    public function hasStarted(): bool
    {
        $now = Carbon::now();

        return $now->greaterThanOrEqualTo($this->startAt);
    }

    public function hasEnded(): bool
    {
        $now = Carbon::now();

        return $now->greaterThan($this->endAt);
    }

    public function isRegisterCandidate(Candidate $candidate): bool
    {
        $uuids = array_column($this->candidates, 'uuid');
        return in_array($candidate->uuid, $uuids);
    }

    public function addCandidate(Candidate $candidate): bool
    {
        $this->candidates[] = $candidate;

        return true;
    }

    public function addVote(Vote $vote): bool
    {
        $this->votes[] = $vote;

        return true;
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
        return;
    }

    private function setVotes(array $votes): void
    {
        $this->checkVotes($votes);
        $this->votes[] = $votes;
    }

    private function checkVotes(array $votes): void
    {
        foreach ($votes as $vote) {
            if (!$vote instanceof Vote) throw new \Exception("Votes invalid", 400);
        }
        return;
    }
}
