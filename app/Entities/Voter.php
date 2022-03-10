<?php

namespace App\Entities;

use DateTime;

class Voter extends Entity
{
    private string $uuid;
    private string $name;
    private string $email;
    private string $password;
    private string $document;
    private bool $enabled;
    private DateTime $createdAt;
    private DateTime $updatedAt;
}
