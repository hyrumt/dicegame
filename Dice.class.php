<?php
class Dice {
    private $number;
    private $value;

    public function roll() {
        $this->number = mt_rand(1, 6);
        $this->value = $this->number == 4 ? 0 : $this->number;
    }

    public function getNumber() {
        return $this->number;
    }

    public function getValue() {
        return $this->value;
    }
}
?>
