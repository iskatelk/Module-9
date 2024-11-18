<?php

abstract class Shape
{
    public $color;

    public function _construct($color)
    {
        $this->color = $color;
    }

    abstract public function getArea(): float;

    public function getColor()
    {
        return $this->color;
    }
}


class Circle extends Shape
{
    public $color;
    public $radius;

    public function __construct($color, $radius)
    {
        $this->color = $color;
        $this->radius = $radius;
    }

    public function getArea(): float
    {
        return $this->radius * $this->radius * 3.14;
    }
}

$Circle = new Circle('red', 2);
var_dump($Circle->getArea());