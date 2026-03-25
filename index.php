<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Sendama\Engine\Core\Game;
use App\Scenes\TitleScene;
use App\Scenes\GameScene;
use App\Scenes\GameOverScene;
use App\Scenes\VictoryScene;

// Load configuration
$config = json_decode(file_get_contents(__DIR__ . '/sendama.json'), true);

// Initialize game
$game = new Game($config['name'], $config['width'], $config['height'], $config['fps']);

// Register scenes
$game->addScene('title', new TitleScene());
$game->addScene('game', new GameScene());
$game->addScene('gameover', new GameOverScene());
$game->addScene('victory', new VictoryScene());

// Start with title scene
$game->loadScene('title');

// Run game loop
$game->run();
