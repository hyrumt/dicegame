<?php
require_once "Player.class.php";

class GoForBroke extends Player {

    public function __construct($name) {
        $this->name = $name;
        $this->currentPoints = 0;
        $this->totalPoints = 0;
    }

    public function rollDice() {
        $numDice = 5;
        $points = 0;
        $kept = "";

        if (LOGGING) echo $this->getName()." is rolling . . .\n";

        while ($numDice > 0) {
            $dice = $this->toss($numDice);

            $minDie = 0;
            $keepMinDie = true;
            foreach($dice as $i => $die) {
                // only keeps 4s / 0s
                if ($die->getNumber() == 4) {
                    $kept .= $die->getNumber().", ";
                    $numDice--;
                    $keepMinDie = false;
                }
                if ($die->getValue() < $dice[$minDie]->getValue()) {
                    $minDie = $i;
                }
            }

            if ($keepMinDie) {
                $kept .= $dice[$minDie]->getNumber().", ";
                $points += $dice[$minDie]->getValue();
                $numDice--;
            }

            if (LOGGING) echo " - kept $kept\n";
        }

        $this->setCurrentPoints($points);
    }
}
?>
