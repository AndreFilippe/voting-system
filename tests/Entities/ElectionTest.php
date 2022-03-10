<?php

namespace Test\Entities;

use App\Entities\Candidate;
use App\Entities\Election;
use App\Entities\Vote;
use App\Entities\Voter;
use Carbon\Carbon;
use Test\TestCase;

/**
 * @covers Election::
 */
class ElectionTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCreatedInstanceElection()
    {
        $candidate = $this->mockCandidate("123456789");
        $election = $this->mockElection([$candidate]);

        $this->assertInstanceOf(Election::class, $election);
        $this->assertEquals('test', $election->description);
        $this->assertTrue($election->enabled);
    }

    /**
     * @test
     */
    public function itShouldReturnCandidatesInvalid()
    {
        $this->expectExceptionMessage('Candidates invalid');
        $this->expectExceptionCode(400);

        $candidate = $this->mockCandidate("123456789");
        $this->mockElection([$candidate, 'test']);
    }

    /**
     * @test
     */
    public function itShouldRegisterCandidate()
    {
        $this->setDateTime('2022-03-09 20:22:00');
        $candidate = $this->mockCandidate("123456789");
        $election = $this->mockElection();

        $election->registerCandidate($candidate);
        $candidates = $this->getProperty($election, 'candidates');
        $this->assertTrue(in_array($candidate, $candidates));
    }

    /**
     * @test
     */
    public function itNotShouldRegisterCandidateAfterStart()
    {
        $election = $this->mockElection();
        $candidate = $this->mockCandidate("123456789");

        $dateTimes = ['2022-03-09 20:23:00', '2022-03-09 20:24:00'];
        //dataProvider not working
        foreach ($dateTimes as $dateTime) {
            $this->setDateTime($dateTime);
            $this->expectExceptionMessage('Election has already started');
            $this->expectExceptionCode(400);
            $election->registerCandidate($candidate);
        }
    }

    /**
     * @test
     */
    public function itNotShouldRegisterCandidateDuplicate()
    {
        $this->setDateTime('2022-03-09 20:20:00');
        $candidate = $this->mockCandidate("123456789");
        $election = $this->mockElection([$candidate]);

        $this->expectExceptionMessage('Candidate is already registered');
        $this->expectExceptionCode(400);

        $election->registerCandidate($candidate);
    }

    /**
     * @test
     */
    public function itShouldRegisterVote()
    {
        $this->setDateTime('2022-03-09 20:23:00');
        $candidate = $this->mockCandidate('abc');
        $vote = $this->mockVote($candidate);
        $election = $this->mockElection([$candidate]);

        $this->assertTrue($election->registerVote($vote));

        $votes = $this->getProperty($election, 'votes');
        $this->assertTrue(in_array($vote, $votes));
    }

    /**
     * @test
     */
    public function itNotShouldRegisterVoteBeforeStart()
    {
        $this->setDateTime('2022-03-09 20:22:59');
        $vote = $this->mockVote($this->mockCandidate('abc'));
        $election = $this->mockElection();

        $this->expectExceptionMessage('Election has not already started');
        $this->expectExceptionCode(400);

        $election->registerVote($vote);
    }

    /**
     * @test
     */
    public function itNotShouldRegisterVoteAfterEnd()
    {
        $this->setDateTime('2022-03-10 20:23:01');
        $vote = $this->mockVote($this->mockCandidate('abc'));
        $election = $this->mockElection();

        $this->expectExceptionMessage('Election has already ended');
        $this->expectExceptionCode(400);

        $election->registerVote($vote);
    }

    /**
     * @test
     */
    public function itNotShouldRegisterVoteToNotRegisteredCandidate()
    {
        $this->setDateTime('2022-03-09 20:23:01');
        $vote = $this->mockVote($this->mockCandidate('abc'));
        $election = $this->mockElection([$this->mockCandidate('def')]);

        $this->expectExceptionMessage('Not registered candidate');
        $this->expectExceptionCode(400);

        $election->registerVote($vote);
    }

    private function mockCandidate(string $uuid): Candidate
    {
        return new Candidate($uuid);
    }

    private function mockElection(array $candidates = []): Election
    {
        return new Election(
            description: "test",
            startAt: new Carbon('2022-03-09 20:23:00'),
            endAt: new Carbon('2022-03-10 20:23:00'),
            enabled: true,
            candidates: $candidates
        );
    }

    private function mockVote(Candidate $candidate): Vote
    {
        $voter = new Voter();
        return new Vote(candidate: $candidate, voter: $voter);
    }
}
