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

class Game {
    private $players;
    private $gameDie;

    function __construct($numPlayers) {
        $this->gameDie = new Dice();
        $this->createPlayerList($numPlayers);
    }

    public function play() {
        if ($logging) echo "started new game\n";
        foreach($this->players as &$player) {
            $player->rollDice();
        }
    }

    public function rotatePlayers() {
        array_push($this->players, array_shift($this->players));
    }

    public function getScores() {
        $scores = [];
        foreach($this->players as $player) {
            $scores[$player->getTotalPoints()] = ['name' => $player->getName(), 'points' => $player->getTotalPoints()];
        }
        ksort($scores);
        return array_values($scores);
    }

    public function printScores() {
        $scores = $this->getScores();
        foreach ($scores as $score) {
            echo $score['name']." - ".$score['points']."\n";
        }
    }

    public function getWinners() {
        $winners = [];
        foreach ($this->players as $player) {
            if (empty($winners) || $player->getTotalPoints() == $winners[0]->getTotalPoints()) {
                $winners[] = $player;
            } elseif ($player->getTotalPoints() < $winners[0]->getTotalPoints()) {
                $winners = [0 => $player];
            }
        }
        return $winners;
    }

    private function createPlayerList($numPlayers) {
        $playerNames = ['Sally', 'Timmy', 'Joe', 'Diana', 'Dave', 'Bob'];
        shuffle($playerNames);

        $this->players = [];
        while(count($this->players) < $numPlayers) {
            $this->gameDie->roll();
            $playerName = array_pop($playerNames);
            switch ($this->gameDie->getNumber()) {
                case 1:
                case 2:
                    $this->players[] = new LowBaller($playerName);
                    break;
                case 3:
                case 4:
                    $this->players[] = new Steady($playerName);
                    break;
                case 5:
                case 6:
                    $this->players[] = new GoForBroke($playerName);
                    break;
            }
        }

        // picks a random player to start
        shuffle($this->players);

        if ($logging) {
            echo "these are the players: \n";
            foreach ($this->players as $player) { echo "\t".$player->getName()."\n"; }
        }
    }
}

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
        if ($logging) echo "\tcurrent points is ".$this->currentPoints.", total points is ".$this->totalPoints."\n";
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
        if ($logging) {
            echo "\trolled: "; foreach($dice as $die) echo $die->getNumber().", ";
        }

        return $dice;
    }
}

class LowBaller extends Player {

    function __construct($name) {
        $this->name = $name;
        $this->currentPoints = 0;
        $this->totalPoints = 0;
    }

    public function rollDice() {
        $numDice = 5;
        $points = 0;

        if ($logging) echo $this->getName()." is rolling . . .\n";

        while ($numDice > 0) {
            $dice = $this->toss($numDice);

            if ($logging) echo " - kept ";
            $minDie = 0;
            $keepMinDie = true;
            foreach($dice as $i => $die) {
                // keeps 1s 2s and 4s
                if ($die->getValue() <= 2) {
                    if ($logging) echo $die->getNumber().", ";
                    $points += $die->getValue();
                    $numDice--;
                    $keepMinDie = false;
                }
                if ($die->getValue() < $dice[$minDie]->getValue()) {
                    $minDie = $i;
                }
            }

            if ($keepMinDie) {
                if ($logging) echo $dice[$minDie]->getNumber().", ";
                $points += $dice[$minDie]->getValue();
                $numDice--;
            }

            if ($logging) echo "\n";
        }

        $this->setCurrentPoints($points);
    }
}

class Steady extends Player {

    function __construct($name) {
        $this->name = $name;
        $this->currentPoints = 0;
        $this->totalPoints = 0;
    }

    public function rollDice() {
        $numDice = 5;
        $points = 0;

        if ($logging) echo $this->getName()." is rolling . . .\n";

        while ($numDice > 0) {
            $dice = $this->toss($numDice);

            if ($logging) echo " - kept ";
            $minDie = 0;
            $keepMinDie = true;
            foreach($dice as $i => $die) {
                // rerolls 5 and 6s
                if ($die->getNumber() <= 4) {
                    if ($logging) echo $die->getNumber().", ";
                    $points += $die->getValue();
                    $numDice--;
                    $keepMinDie = false;
                }
                if ($die->getValue() < $dice[$minDie]->getValue()) {
                    $minDie = $i;
                }
            }

            if ($keepMinDie) {
                if ($logging) echo $dice[$minDie]->getNumber().", ";
                $points += $dice[$minDie]->getValue();
                $numDice--;
            }
            if ($logging) echo "\n";
        }

        $this->setCurrentPoints($points);
    }
}

class GoForBroke extends Player {

    function __construct($name) {
        $this->name = $name;
        $this->currentPoints = 0;
        $this->totalPoints = 0;
    }

    public function rollDice() {
        $numDice = 5;
        $points = 0;

        if ($logging) echo $this->getName()." is rolling . . .\n";

        while ($numDice > 0) {
            $dice = $this->toss($numDice);

            $minDie = 0;
            $keepMinDie = true;
            if ($logging) echo " - kept ";
            foreach($dice as $i => $die) {
                // only keeps 4s
                if ($die->getNumber() == 4) {
                    if ($logging) echo $die->getNumber().", ";
                    $numDice--;
                    $keepMinDie = false;
                }
                if ($die->getValue() < $dice[$minDie]->getValue()) {
                    $minDie = $i;
                }
            }

            if ($keepMinDie) {
                if ($logging) echo $dice[$minDie]->getNumber().", ";
                $points += $dice[$minDie]->getValue();
                $numDice--;
            }
            if ($logging) echo "\n";
        }

        $this->setCurrentPoints($points);
    }
}

function playGame($numPlayers, $numRounds) {
    $game = new Game($numPlayers);
    for ($i = 0; $i < $numRounds; $i++) {
        $game->play();
        if ($logging) $game->printScores();
        $game->rotatePlayers();
    }
    $winners = $game->getWinners();
    if (count($winners) > 1) {
        $winnerNames = [];
        foreach ($winners as $winner) {
            $winnerNames[] = $winner->getName();
        }
        echo "The winners are ".implode(" and ", $winnerNames);
    } else {
        echo "The winner is ".$winners[0]->getName();
    }
    echo " with {$winners[0]->getTotalPoints()} points!\n";
}

$logging = false;
playGame(4, 4);
?>
