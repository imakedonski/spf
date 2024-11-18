<?php

use PHPUnit\Framework\TestCase;
use SPF\Database\Database;


class DatabaseTest extends TestCase
{
    private string $dsn;
    private string $user = '';
    private string $password = '';

    public function setUp(): void
    {
        $this->dsn = 'sqlite::memory:';
    }

    public function testDatabaseConnection(): void
    {
        $database = Database::getInstance($this->dsn, $this->user, $this->password);

        $this->assertInstanceOf(PDO::class, $database);
    }

}