<?php 

declare(strict_types=1);

namespace PricingComparison\Model\Offer;

use PricingComparison\Model\Build;
use PricingComparison\Model\Buildable;

final class Offer implements Buildable, Mapable
{
    use Build;

    private $product;
    private $units;
    private $price;
    private $pricePerUnit;
    private $currency;

    private function __construct (
        string $product,
        int $units,
        float $price,
        string $currency
    ) {

        if (strlen(trim($product)) < 1) {
            throw new OfferDomainException('offer_invalid_product');
        }

        if (strlen(trim($currency)) !== 3) {
            throw new OfferDomainException('offer_invalid_currency');            
        }

        if ($units < 1) {
            throw new OfferDomainException('offer_invalid_units');
        }

        if ($price <= 0.0) {
            throw new OfferDomainException('offer_invalid_price');
        }

        $this->product = $product;
        $this->units = $units;
        $this->price = $price;
        $this->currency = $currency;
        $this->pricePerUnit = $price/$units;
    }

    public function getKey(): string
    {
        return $this->product;
    }

    public function getUnits(): int
    {
        return $this->units;
    }

    public function getPricePerUnit(): float
    {
        return $this->pricePerUnit;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency; 
    }
 
}