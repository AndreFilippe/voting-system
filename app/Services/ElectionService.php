<?php

namespace App\Services;

use App\Entities\Candidate;
use App\Entities\Election;
use App\Entities\Vote;

class ElectionService
{
    public function __construct(private Election $election)
    {
    }

    public function registerCandidate(Candidate $candidate): bool
    {
        if ($this->election->hasStarted()) {
            throw new \Exception("Election has already started", 400);
        }

        if ($this->election->isRegisterCandidate($candidate)) {
            throw new \Exception("Candidate is already registered", 400);
        }

        $this->election->addCandidate($candidate);

        return true;
    }

    public function registerVote(Vote $vote): bool
    {
        if (!$this->election->hasStarted()) {
            throw new \Exception("Election has not already started", 400);
        }

        if ($this->election->hasEnded()) {
            throw new \Exception("Election has already ended", 400);
        }

        if (!$this->election->isRegisterCandidate($vote->candidate)) {
            throw new \Exception("Not registered candidate", 400);
        }

        $this->election->addVote($vote);

        return true;
    }
}
