<?php

namespace App\Services;

use App\Enums\PublishingModel;

/**
 * Publishing Triage Routing Engine
 * 
 * Evaluates author submissions and classifies projects into Traditional Acquisition
 * or the Osisi Hybrid Model based on author demographics, genre metrics, and platform reach.
 * 
 * EXTENSION NOTE FOR FUTURE DEVELOPERS:
 * You can easily edit or extend this class to append backend yardsticks such as:
 * - Historical sales velocity ($extraMetrics['past_sales_velocity'])
 * - Micro/sub-genre market trend demand ($extraMetrics['subgenre_demand_score'])
 * - Literary agent tier rankings ($extraMetrics['agent_tier_rank'])
 */
class PublishingTriageService
{
    /**
     * Customizable extension evaluation yardsticks.
     * Developers can register callable hooks that receive the author context.
     *
     * @var array<callable>
     */
    protected array $customYardsticks = [];

    /**
     * Register an additional triage yardstick.
     */
    public function registerYardstick(callable $callback): self
    {
        $this->customYardsticks[] = $callback;
        return $this;
    }

    /**
     * Determine the publishing model for an incoming submission.
     *
     * @param int|null $authorAge
     * @param string $genre
     * @param bool $hasSocialPresence
     * @param bool $isEstablished
     * @param array $extraContext Additional yardstick parameters
     * @return PublishingModel
     */
    public function determinePublishingModel(
        ?int $authorAge,
        string $genre,
        bool $hasSocialPresence = false,
        bool $isEstablished = false,
        array $extraContext = []
    ): PublishingModel {
        $cleanGenre = trim(strtolower($genre));
        $isNonFiction = in_array($cleanGenre, ['non-fiction', 'nonfiction', 'biography', 'memoir', 'business', 'academic', 'history', 'self-help', 'non-fiction / memoir', 'biography / memoir', 'biography/memoir', 'academic/business', 'academic / business']);
        $isHighPotentialFiction = in_array($cleanGenre, ['fiction', 'sci-fi/fantasy', 'scifi', 'fantasy', 'literary fiction', 'romance', 'thriller', 'african literature', 'sci-fi / fantasy']);

        // 1. Check Custom Extension Yardsticks first (if any return non-null)
        foreach ($this->customYardsticks as $yardstick) {
            $result = $yardstick($authorAge, $genre, $hasSocialPresence, $isEstablished, $extraContext);
            if ($result instanceof PublishingModel) {
                return $result;
            }
        }

        // 2. Strict Osisi Hybrid Rule:
        // "If the author's age is 50 or older AND the genre is 'Non-Fiction',
        //  automatically classify as hybrid_osisi."
        if ($authorAge !== null && $authorAge >= 50 && $isNonFiction) {
            return PublishingModel::HybridOsisi;
        }

        // 3. Traditional Candidate Rule:
        // Qualified if author has an established audience platform (social presence or established author),
        // or is a young breakout talent writing high-potential fiction (<35).
        $isUnder35 = ($authorAge !== null && $authorAge > 0 && $authorAge < 35);

        if ($hasSocialPresence || $isEstablished) {
            return PublishingModel::Traditional;
        }

        if ($isHighPotentialFiction && $isUnder35) {
            return PublishingModel::Traditional;
        }

        // 4. Default non-fiction or authors without established platform route to Osisi Hybrid
        return PublishingModel::HybridOsisi;
    }

    /**
     * Get a descriptive explanation of the triage determination.
     */
    public function getDeterminationReason(
        PublishingModel $model,
        ?int $authorAge,
        string $genre,
        bool $hasSocialPresence,
        bool $isEstablished
    ): string {
        if ($model === PublishingModel::Traditional) {
            $reasons = [];
            if ($authorAge !== null && $authorAge < 35) $reasons[] = 'emerging author under 35';
            if ($hasSocialPresence) $reasons[] = 'strong social media presence';
            if ($isEstablished) $reasons[] = 'established literary track record';
            return 'Pre-qualified for Traditional Acquisition review based on ' . implode(', ', $reasons ?: ['genre alignment']) . '.';
        }

        if ($authorAge !== null && $authorAge >= 50) {
            return 'Classified under the Osisi Hybrid Model (author age demographic & specialized non-fiction profile).';
        }

        return 'Classified under the Osisi Hybrid Model (author-partnered customized distribution & production).';
    }
}

