<?php
require_once "Game.class.php";

define("LOGGING", false);

function playGame($numPlayers, $numRounds) {
    $game = new Game($numPlayers);
    for ($i = 0; $i < $numRounds; $i++) {
        $game->play();
        if (LOGGING) $game->printScores();
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

playGame(4, 4);
?>
