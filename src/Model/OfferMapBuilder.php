<?php 

declare(strict_types=1);

namespace PricingComparison\Model;
//TODO delete??
final class OfferMapBuilder implements OffersBuilder
{

    public function build(Offers $offers): array {
        return $offers->buildSortedMap();
    }

    private function sortMap(array $map) {
        foreach ($map as $key => $value) {
            $map[$key] = $this->sort($map[$key]);
        }
        return $map;
    }

    //sort by pricerPerUnit is the right way to find the cheapest supplier
    private function sort(array $offers) {
        usort($offers,
            function (Offer $o, Offer $u): int
            {   
                if ($o->getPricePerUnit() == $u->getPricePerUnit()) {
                    return 0;
                }
                return ($o->getPricePerUnit() < $u->getPricePerUnit()) ? -1 : 1;
            }
        );

        return $offers;
    }

    private function buildMap(array $instances): array 
    {
        $map = [];
        foreach ($instances as $i) {
            if(!isset($map[$i->getKey()])) {
                $map[$i->getKey()] = [];
            }
            
            array_push($map[$i->getKey()], $i);
        }
        return $map;
    }

    
}