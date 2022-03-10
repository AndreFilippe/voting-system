<?php

namespace Test\Entities;

use App\Entities\Election;
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
    public function itShouldAddCandidate()
    {
        $candidate = $this->mockCandidate("123456789");
        $election = $this->mockElection();

        $this->assertTrue($election->addCandidate($candidate));
        $candidates = $this->getProperty($election, 'candidates');
        $this->assertTrue(in_array($candidate, $candidates));
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
    public function itShouldAddVote()
    {
        $candidate = $this->mockCandidate("123456789");
        $election = $this->mockElection();

        $vote = $this->mockVote($candidate);
        $this->assertTrue($election->addVote($vote));

        $votes = $this->getProperty($election, 'votes');
        $this->assertTrue(in_array($vote, $votes));
    }

    /**
     * @test
     */
    public function itShouldReturnVotesInvalid()
    {
        $this->expectExceptionMessage('Votes invalid');
        $this->expectExceptionCode(400);

        $candidate = $this->mockCandidate("123456789");
        $vote = $this->mockVote($candidate);
        $this->mockElection(votes: [$vote, 'teste']);
    }

    /**
     * @test
     */
    public function itShouldReturnTrueAfterStart()
    {
        $election = $this->mockElection();

        $dateTimes = ['2022-03-09 20:23:00', '2022-03-09 20:24:00'];
        //dataProvider not working
        foreach ($dateTimes as $dateTime) {
            $this->setDateTime($dateTime);
            $this->assertTrue($election->hasStarted());
        }
    }

    /**
     * @test
     */
    public function itShouldReturnFalseBeforeStart()
    {
        $this->setDateTime('2022-03-09 20:22:59');
        $election = $this->mockElection();

        $this->assertfalse($election->hasStarted());
    }

    /**
     * @test
     */
    public function itShouldReturnTrueAfterEnd()
    {
        $this->setDateTime('2022-03-10 20:23:01');
        $election = $this->mockElection();

        $this->assertTrue($election->hasEnded());
    }

    /**
     * @test
     */
    public function itShouldReturnFalseBeforeEnd()
    {
        $this->setDateTime('2022-03-10 20:23:00');
        $election = $this->mockElection();

        $this->assertFalse($election->hasEnded());
    }

    /**
     * @test
     */
    public function itShouldReturnTrueWhenCandidateHasregistered()
    {
        $this->setDateTime('2022-03-09 20:20:00');
        $candidate = $this->mockCandidate("123456789");
        $election = $this->mockElection([$candidate]);

        $this->assertTrue($election->isRegisterCandidate($candidate));
    }

    /**
     * @test
     */
    public function itShouldReturnFalseWhenCandidateHasNotRegistered()
    {
        $this->setDateTime('2022-03-09 20:20:00');
        $election = $this->mockElection();

        $candidate = $this->mockCandidate("123456789");
        $this->assertFalse($election->isRegisterCandidate($candidate));
    }
}
