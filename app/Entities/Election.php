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

    public function registerCandidate(Candidate $candidate): bool
    {
        if ($this->hasStarted()) {
            throw new \Exception("Election has already started", 400);
        }

        if ($this->isRegisterCandidate($candidate)) {
            throw new \Exception("Candidate is already registered", 400);
        }

        $this->candidates[] = $candidate;

        return true;
    }

    private function hasStarted(): bool
    {
        $now = Carbon::now();

        return $now->greaterThanOrEqualTo($this->startAt);
    }

    private function isRegisterCandidate(Candidate $candidate): bool
    {
        $uuids = array_column($this->candidates, 'uuid');
        return in_array($candidate->uuid, $uuids);
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
