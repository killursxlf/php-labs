<?php
declare(strict_types=1);

class Product
{
    public string $name;
    public string $description;
    protected float $price;

    public function __construct(string $name, float $price, string $description = '')
    {
        if ($price < 0) 
        {
            throw new InvalidArgumentException('Price cannot be negative');
        }
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getInfo(): string
    {
        $lines = 
        [
            'Name: ' . $this->name,
            'Price: €' . number_format($this->price, 2),
            'Description: ' . ($this->description !== '' ? $this->description : '—'),
        ];
        return implode("\n", $lines);
    }
}
?>