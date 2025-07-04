<?php

namespace Idsqm\Sitemap;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use InvalidArgumentException;

class SitemapRecord
{
    private string $loc;

    private ?CarbonInterface $lastMod;

    private ?Freq $changeFreq;

    private ?float $priority;

    /**
     * @param string $loc
     * @param CarbonInterface|string|null $lastMod
     * @param Freq|string|null $changeFreq
     * @param float|null $priority
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $loc,
        CarbonInterface|string|null $lastMod = null,
        Freq|string|null $changeFreq = null,
        float|null $priority = null
    )
    {
        $this->setLoc($loc);
        $this->setLastMod($lastMod);
        $this->setChangeFreq($changeFreq);
        $this->setPriority($priority);
    }

    public function setLoc(string $loc): void
    {
        $this->loc = $loc;
    }

    public function setLastMod(CarbonInterface|string|null $lastMod): void
    {
        if (is_string($lastMod)) {
            $lastMod = Carbon::parse($lastMod);
        }

        $this->lastMod = $lastMod;
    }

    public function setChangeFreq(Freq|string|null $changeFreq): void
    {
        if (is_string($changeFreq)) {
            $changeFreq = Freq::from($changeFreq);
        }

        $this->changeFreq = $changeFreq;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setPriority(float|null $priority): void
    {
        if ($priority < 0 || $priority > 1.0) {
            throw new InvalidArgumentException("Priority must be between 0 and 1.0");
        }

        $this->priority = $priority;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function getLastMod(): ?string
    {
        return $this->lastMod?->toW3cString();
    }

    public function getChangeFreq(): ?Freq
    {
        return $this->changeFreq;
    }

    public function getPriority(): ?float
    {
        return $this->priority;
    }
}