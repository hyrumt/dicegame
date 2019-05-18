<?php
require_once "Dice.class.php";
require_once "LowBaller.class.php";
require_once "Steady.class.php";
require_once "GoForBroke.class.php";

class Game {
    private $players;
    private $gameDie;

    function __construct($numPlayers) {
        $this->gameDie = new Dice();
        $this->createPlayerList($numPlayers);
    }

    public function play() {
        if (LOGGING) echo "started new game\n";
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
            $scores[] = ['name' => $player->getName(), 'points' => $player->getTotalPoints()];
        }
        return $scores;
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

        if (LOGGING) {
            echo "these are the players: \n";
            foreach ($this->players as $player) { echo "\t".$player->getName()."\n"; }
        }
    }
}
?>
