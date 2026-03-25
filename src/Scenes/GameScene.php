<?php

declare(strict_types=1);

namespace App\Scenes;

use Sendama\Engine\Core\Scene;
use Sendama\Engine\Core\Input;
use App\Game\Maze;
use App\Entities\Player;

/**
 * Game Scene - Main gameplay where player navigates the maze
 * 
 * Demonstrates:
 * - Game loop integration
 * - Entity management
 * - Collision detection
 * - Win condition checking
 * - HUD rendering
 */
class GameScene extends Scene
{
    private Maze $maze;
    private Player $player;
    private array $mazeRender;
    private bool $paused = false;
    private string $statusMessage = '';
    private float $statusTimer = 0;
    
    public function awake(): void
    {
        // Initialize maze
        $this->maze = new Maze(41, 21);
        $this->maze->generate();
        
        // Initialize player at maze start
        $start = $this->maze->getStart();
        $this->player = new Player($this->maze, $start['x'], $start['y']);
        
        // Pre-render maze
        $this->mazeRender = $this->maze->render();
    }
    
    public function update(float $deltaTime): void
    {
        if ($this->paused) {
            if (Input::isKeyPressed(Input::KEY_ESCAPE) || Input::isKeyPressed(Input::KEY_P)) {
                $this->paused = false;
            }
            return;
        }
        
        // Check pause
        if (Input::isKeyPressed(Input::KEY_ESCAPE) || Input::isKeyPressed(Input::KEY_P)) {
            $this->paused = true;
            return;
        }
        
        // Update player
        $this->player->update();
        
        // Check win condition
        if ($this->player->hasEscaped()) {
            $stats = [
                'moves' => $this->player->getMoves(),
                'time' => round($this->player->getElapsedTime(), 2)
            ];
            $this->game->setData('victory_stats', $stats);
            $this->game->loadScene('victory');
            return;
        }
        
        // Check quit
        if (Input::isKeyPressed(Input::KEY_Q)) {
            $this->game->loadScene('title');
        }
        
        // Status message timer
        if ($this->statusTimer > 0) {
            $this->statusTimer -= $deltaTime;
            if ($this->statusTimer <= 0) {
                $this->statusMessage = '';
            }
        }
    }
    
    public function render(): void
    {
        $this->clear();
        
        if ($this->paused) {
            $this->renderPauseMenu();
            return;
        }
        
        // Draw maze
        for ($y = 0; $y < count($this->mazeRender); $y++) {
            $this->drawText(4, 2 + $y, $this->mazeRender[$y]);
        }
        
        // Draw player
        $pos = $this->player->getPosition();
        $this->drawText(4 + $pos['x'], 2 + $pos['y'], $this->player->getSprite());
        
        // Draw exit marker
        $exit = $this->maze->getExit();
        $this->drawText(4 + $exit['x'], 2 + $exit['y'], '◈');
        
        // Draw HUD
        $this->drawText(4, 25, '═══ STATUS ════════════════════════════════════════');
        $this->drawText(6, 26, sprintf('MOVES: %d  |  TIME: %.1fs  |  GOAL: Find ◈', 
            $this->player->getMoves(), 
            $this->player->getElapsedTime()
        ));
        
        // Draw controls
        $this->drawText(4, 28, 'Controls: WASD/Arrows = Move  |  P = Pause  |  Q = Quit');
        
        // Draw status message if any
        if ($this->statusMessage) {
            $this->drawText(25, 15, $this->statusMessage);
        }
        
        // Draw quantum flavor text
        $this->drawFlavorText();
    }
    
    private function renderPauseMenu(): void
    {
        // Semi-transparent overlay effect
        $this->drawText(30, 10, '╔══════════════════════╗');
        $this->drawText(30, 11, '║      P A U S E D     ║');
        $this->drawText(30, 12, '╠══════════════════════╣');
        $this->drawText(30, 13, '║ P or ESC to resume   ║');
        $this->drawText(30, 14, '║ Q to quit to menu    ║');
        $this->drawText(30, 15, '╚══════════════════════╝');
    }
    
    private function drawFlavorText(): void
    {
        $flavors = [
            'Quantum fluctuations detected...',
            'The maze shifts when you look away.',
            'Starlight guides your path.',
            'Dimensional stability: 94%',
            'Neutrino readings nominal.'
        ];
        
        // Change flavor occasionally based on moves
        $index = floor($this->player->getMoves() / 10) % count($flavors);
        $this->drawText(50, 26, $flavors[$index]);
    }
    
    public function setStatus(string $message, float $duration = 2.0): void
    {
        $this->statusMessage = $message;
        $this->statusTimer = $duration;
    }
}
