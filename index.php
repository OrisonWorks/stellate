<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Sendama\Engine\Game;
use App\Scenes\TitleScene;
use App\Scenes\GameScene;
use App\Scenes\GameOverScene;
use App\Scenes\VictoryScene;

// Load configuration
$config = json_decode(file_get_contents(__DIR__ . '/sendama.json'), true);

// Initialize game
$game = new Game($config['name'], $config['width'], $config['height'], $config['fps']);

// Register scenes
$game->addScenes(new TitleScene(), new GameScene(), new GameOverScene(), new VictoryScene());

// Run game loop
$game->run();
