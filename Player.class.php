<?php
require_once "Dice.class.php";

abstract class Player {
    protected $currentPoints;
    protected $totalPoints;
    protected $name;

    abstract public function rollDice();

    public function getName() {
        return $this->name." (".get_class($this).")";
    }

    public function getCurrentPoints() {
        return $this->currentPoints;
    }

    protected function setCurrentPoints($points) {
        $this->currentPoints = $points;
        $this->totalPoints += $points;
        if (LOGGING) echo "\tcurrent points is ".$this->currentPoints.", total points is ".$this->totalPoints."\n";
    }

    public function getTotalPoints() {
        return $this->totalPoints;
    }

    protected function toss($numDice) {
        $dice = [];
        for ($i = 0; $i < $numDice; $i++) {
            $die = new Dice();
            $die->roll();
            $dice[] = $die;
        }
        if (LOGGING) {
            echo "\trolled: "; foreach($dice as $die) echo $die->getNumber().", ";
        }

        return $dice;
    }
}
?>
