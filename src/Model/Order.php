<?php 

declare(strict_types=1);

namespace PricingComparison\Model;

final class Order
{
    use BuildMany;
    use OfSameClass;

    private $orderItems;
    private $suppliers;
    private $costs;
    private $result;
    private $costBuilder;
    private $resultMessage;

    private function __construct (
        OrderItems $orderItems,
        Suppliers $suppliers,
        CostBuilderInterface $costBuilder
    ) {
        
        $this->orderItems = $orderItems;
        $this->suppliers = $suppliers;
        $this->costBuilder = $costBuilder;
        $this->calcResultSteps();
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function getResultMessage(): ResultMessage
    {
        return $this->resultMessage;
    }

    public static function build(OrderItems $orderItems, Suppliers $suppliers)
    {
        return new static($orderItems, $suppliers, new CostBuilder());
    }

    private function calcResultSteps(){
        $this->calcCosts();
        $this->calcCheapest();
        $this->makeResultMessage();
    }

    private function calcCosts() 
    {
        $costs = [];
        foreach ($this->suppliers->toArray() as $supplier) {
            $cost = $this->costBuilder->build(
                $supplier, $this->orderItems
            );
            $costs [] = $cost;
        }

        $this->costs = new Costs($costs);
    }

    private function calcCheapest()
    {
        $this->result = $this->costs->calcCheapest()->getResult();
    } 

    private function makeResultMessage() {
        $this->resultMessage = new ResultMessage(
            $this->orderItems->getResultText(),
            $this->result->getResultText(),
            $this->costs->getResultText()
        );
    }
}

/**  
 * Example 1
 * Customer wants to buy 5 Units Dental Floss and 12 Units Ibuprofen.
 * 
 *     Cost Supplier A:
 *     5 x 1 Unit Dental Floss - 45 EUR
 *     1 x 10 Units Ibuprofen - 48 EUR
 *     2 x 1 Unit Ibuprofen - 10 EUR
 *     Total: 103 EUR
* 
*     Cost Supplier B:
*     5 x 1 Unit Dental Floss - 40 EUR
*     2 x 5 Units Ibuprofen - 50 EUR
*     2 x 1 Unit Ibuprofen - 12 EUR
*     Total: 102 EUR
*     
*     Result: Supplier B is cheaper - 102 EUR
* 
* Example 2
* Customer wants to buy 105 Units Ibuprofen
* 
*     Cost Supplier A:
*     10 x 10 Units Ibuprofen - 480 EUR
*     5 x 1 Unit Ibuprofen - 25 EUR
*     Total: 505 EUR
* 
*     Cost Supplier B:
*     1 x 100 Units Ibuprofen - 410 EUR
*     1 x 5 Units Ibuprofen - 25 EUR
*     Total: 435 EUR
* 
*     Result: Supplier B is cheaper - 435 EUR
*/