<?php
declare(strict_types=1);

class DiscountedProduct extends Product
{
    private float $discount;

    public function __construct(string $name, float $price, string $description = '', float $discount = 0.0)
    {
        parent::__construct($name, $price, $description);
        if ($discount < 0 || $discount > 100) 
        {
            throw new InvalidArgumentException('Discount must be between 0 and 100');
        }
        $this->discount = $discount;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getDiscountedPrice(): float
    {
        $p = $this->price * (1 - $this->discount / 100);
        return round($p, 2);
    }

    public function getInfo(): string
    {
        $lines = 
        [
            'Name: ' . $this->name,
            'Price: €' . number_format($this->price, 2),
            'Discount: ' . number_format($this->discount, 2) . '%',
            'New price: €' . number_format($this->getDiscountedPrice(), 2),
            'Description: ' . ($this->description !== '' ? $this->description : '—'),
        ];
        return implode("\n", $lines);
    }
}
?>