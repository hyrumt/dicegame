<?php
require_once "Player.class.php";

class GoForBroke extends Player {

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

            $minDie = 0;
            $keepMinDie = true;
            if (LOGGING) echo " - kept ";
            foreach($dice as $i => $die) {
                // only keeps 4s
                if ($die->getNumber() == 4) {
                    if (LOGGING) echo $die->getNumber().", ";
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
