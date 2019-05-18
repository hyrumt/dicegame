<?php
require_once "Player.class.php";

class LowBaller extends Player {

    function __construct($name) {
        $this->name = $name;
        $this->currentPoints = 0;
        $this->totalPoints = 0;
    }

    public function rollDice() {
        $numDice = 5;
        $points = 0;

        if (LOGGING) echo $this->getName()." is rolling . . .\n";

        while ($numDice > 0) {
            $dice = $this->toss($numDice);

            if (LOGGING) echo " - kept ";
            $minDie = 0;
            $keepMinDie = true;
            foreach($dice as $i => $die) {
                // keeps 1s 2s and 4s
                if ($die->getValue() <= 2) {
                    if (LOGGING) echo $die->getNumber().", ";
                    $points += $die->getValue();
                    $numDice--;
                    $keepMinDie = false;
                }
                if ($die->getValue() < $dice[$minDie]->getValue()) {
                    $minDie = $i;
                }
            }

            if ($keepMinDie) {
                if (LOGGING) echo $dice[$minDie]->getNumber().", ";
                $points += $dice[$minDie]->getValue();
                $numDice--;
            }

            if (LOGGING) echo "\n";
        }

        $this->setCurrentPoints($points);
    }
}
?>
