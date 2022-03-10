<?php

namespace App\Entities;

use DateTime;

class Candidate extends Entity
{
    private string $name;
    private string $document;
    private string $photoUrl;
    private bool $enabled;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(public readonly string $uuid)
    {
    }
}
