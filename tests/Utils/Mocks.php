<?php

namespace Test\Utils;

use App\Entities\Candidate;
use App\Entities\Election;
use App\Entities\Vote;
use App\Entities\Voter;
use App\Services\ElectionService;
use Carbon\Carbon;

trait Mocks
{
    protected function mockCandidate(string $uuid): Candidate
    {
        return new Candidate($uuid);
    }

    protected function mockElection(array $candidates = [], array $votes = []): Election
    {
        return new Election(
            description: "test",
            startAt: new Carbon('2022-03-09 20:23:00'),
            endAt: new Carbon('2022-03-10 20:23:00'),
            enabled: true,
            votes: $votes,
            candidates: $candidates
        );
    }

    protected function mockVote(Candidate $candidate): Vote
    {
        $voter = new Voter();
        return new Vote(candidate: $candidate, voter: $voter);
    }

    protected function mockElectionService(Candidate $candidate)
    {
        $election = $this->mockElection([$candidate]);
        return new ElectionService($election);
    }
}
