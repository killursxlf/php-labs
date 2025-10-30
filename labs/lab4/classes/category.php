<?php
declare(strict_types=1);

class Category
{
    public string $name;
    private array $products = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getInfoList(): array
    {
        $list = [];
        foreach ($this->products as $p) {
            $list[] = $p->getInfo();
        }
        return $list;
    }
}
?>