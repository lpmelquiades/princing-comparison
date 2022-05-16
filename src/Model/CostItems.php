<?php 

declare(strict_types=1);

namespace PricingComparison\Model;

final class CostItems
{

    private $set;

    public function __construct(array $entries)
    {
        if(empty($entries)) {
            throw new \DomainException('empty_entries');
        }
        $this->set = new \Ds\Set($entries);   
    }

    public function isEmpty(): bool 
    {
        return $this->set->isEmpty();
    }

    public function toArray(): array 
    {
        return $this->set->toArray();
    }

    public function getCurrency(): string
    {
        return $this->set->get(0)->getCurrency();
    }

    public function getTotalPrice(): float {
        $totalPrice = 0.0;
        foreach ($this->set->toArray() as $costItem) {
            $totalPrice += $costItem->getTotalPrice();
        }
        return $totalPrice;
    }

    public function getResultText(): string
    {
        $text = '';
        foreach ($this->set->toArray() as $costItem) {
            $text .= $costItem->getText() . "\n";
        }
        return $text;
    }
    
}