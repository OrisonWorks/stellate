<?php

declare(strict_types=1);

namespace App\Scenes;

use Sendama\Engine\Core\Scene;
use Sendama\Engine\Core\Input;

/**
 * Victory Scene - Shown when player successfully escapes the maze
 * 
 * Displays completion stats and offers replay/quit options.
 */
class VictoryScene extends Scene
{
    private array $stats = [];
    
    public function awake(): void
    {
        // Retrieve stats from game data
        $this->stats = $this->game->getData('victory_stats') ?? [
            'moves' => 0,
            'time' => 0
        ];
    }
    
    public function update(float $deltaTime): void
    {
        if (Input::isKeyPressed(Input::KEY_ENTER) || Input::isKeyPressed(Input::KEY_SPACE)) {
            // Play again
            $this->game->loadScene('game');
        } elseif (Input::isKeyPressed(Input::KEY_Q) || Input::isKeyPressed(Input::KEY_ESCAPE)) {
            // Return to title
            $this->game->loadScene('title');
        }
    }
    
    public function render(): void
    {
        $this->clear();
        
        // Victory banner
        $this->drawText(25, 5, '╔══════════════════════════════════════════╗');
        $this->drawText(25, 6, '║                                          ║');
        $this->drawText(25, 7, '║         ★ E S C A P E   C O M P L E T E ★ ║');
        $this->drawText(25, 8, '║                                          ║');
        $this->drawText(25, 9, '╚══════════════════════════════════════════╝');
        
        // Flavor text
        $this->drawText(20, 11, 'You have successfully navigated the quantum maze!');
        $this->drawText(25, 12, 'The dimensional coordinates are secure.');
        
        // Stats box
        $this->drawText(30, 15, '╔══════════════════════╗');
        $this->drawText(30, 16, '║   M I S S I O N      ║');
        $this->drawText(30, 17, '║     S T A T S        ║');
        $this->drawText(30, 18, '╠══════════════════════╣');
        $this->drawText(30, 19, sprintf('║  Moves:    %4d      ║', $this->stats['moves'] ?? 0));
        $this->drawText(30, 20, sprintf('║  Time:   %6.1fs    ║', $this->stats['time'] ?? 0));
        $this->drawText(30, 21, '╚══════════════════════╝');
        
        // Rating
        $rating = $this->calculateRating();
        $this->drawText(35, 23, 'Rating: ' . $rating);
        
        // Controls
        $this->drawText(22, 26, 'ENTER = Play Again  |  Q/ESC = Main Menu');
    }
    
    private function calculateRating(): string
    {
        $moves = $this->stats['moves'] ?? 0;
        $time = $this->stats['time'] ?? 0;
        
        // Simple rating based on efficiency
        if ($moves < 50 && $time < 30) {
            return '★★★ Quantum Navigator ★★★';
        } elseif ($moves < 100 && $time < 60) {
            return '★★☆ Space Explorer ★★☆';
        } else {
            return '★☆☆ Cadet ☆☆★';
        }
    }
}
