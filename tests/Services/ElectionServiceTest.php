<?php

namespace Test\Services;

use App\Services\ElectionService;
use Test\TestCase;

class ElectionServiceTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldRegisterCandidate()
    {
        $this->setDateTime('2022-03-09 20:22:00');
        $candidate = $this->mockCandidate("123456789");
        $election = $this->mockElection();
        $electionService = new ElectionService($election);

        $this->assertTrue($electionService->registerCandidate($candidate));
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
        $electionService = new ElectionService($election);

        $dateTimes = ['2022-03-09 20:23:00', '2022-03-09 20:24:00'];
        //dataProvider not working
        foreach ($dateTimes as $dateTime) {
            $this->setDateTime($dateTime);
            $this->expectExceptionMessage('Election has already started');
            $this->expectExceptionCode(400);
            $electionService->registerCandidate($candidate);
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
        $electionService = new ElectionService($election);

        $this->expectExceptionMessage('Candidate is already registered');
        $this->expectExceptionCode(400);

        $electionService->registerCandidate($candidate);
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
        $electionService = new ElectionService($election);

        $this->assertTrue($electionService->registerVote($vote));

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
        $electionService = new ElectionService($election);

        $this->expectExceptionMessage('Election has not already started');
        $this->expectExceptionCode(400);

        $electionService->registerVote($vote);
    }

    /**
     * @test
     */
    public function itNotShouldRegisterVoteAfterEnd()
    {
        $this->setDateTime('2022-03-10 20:23:01');
        $vote = $this->mockVote($this->mockCandidate('abc'));
        $election = $this->mockElection();
        $electionService = new ElectionService($election);

        $this->expectExceptionMessage('Election has already ended');
        $this->expectExceptionCode(400);

        $electionService->registerVote($vote);
    }

    /**
     * @test
     */
    public function itNotShouldRegisterVoteToNotRegisteredCandidate()
    {
        $this->setDateTime('2022-03-09 20:23:01');
        $vote = $this->mockVote($this->mockCandidate('abc'));
        $election = $this->mockElection([$this->mockCandidate('def')]);
        $electionService = new ElectionService($election);

        $this->expectExceptionMessage('Not registered candidate');
        $this->expectExceptionCode(400);

        $electionService->registerVote($vote);
    }
}
