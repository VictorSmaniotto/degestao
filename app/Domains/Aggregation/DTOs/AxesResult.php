<?php

namespace App\Domains\Aggregation\DTOs;

readonly class AxesResult
{
    public function __construct(
        public int $x, // Performance Score (0-100)
        public int $y, // Potential Score (0-100)
        public int $evidenceCount,
        public ?int $previousX = null,
        public ?int $previousY = null,
        public string $quadrantLabel = '', // Can be populated later
    ) {
    }
    public function getAverage(): int
    {
        return (int) round(($this->x + $this->y) / 2);
    }
}
