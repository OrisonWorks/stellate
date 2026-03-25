<?php

declare(strict_types=1);

namespace App\Entities;

use App\Game\Maze;
use Sendama\Engine\Core\Input;

/**
 * Player - The astronaut navigating the quantum maze
 * 
 * Demonstrates:
 * - Entity-component pattern
 * - Input handling
 * - Collision detection
 * - State management
 */
class Player
{
    private int $x;
    private int $y;
    private string $sprite;
    private Maze $maze;
    private int $moves = 0;
    private float $startTime;
    
    public function __construct(Maze $maze, int $startX = 1, int $startY = 1)
    {
        $this->maze = $maze;
        $this->x = $startX;
        $this->y = $startY;
        $this->sprite = '☉'; // Star symbol for the player
        $this->startTime = microtime(true);
    }
    
    /**
     * Handle player movement input
     */
    public function update(): void
    {
        $newX = $this->x;
        $newY = $this->y;
        
        if (Input::isKeyPressed(Input::KEY_UP) || Input::isKeyPressed(Input::KEY_W)) {
            $newY--;
        } elseif (Input::isKeyPressed(Input::KEY_DOWN) || Input::isKeyPressed(Input::KEY_S)) {
            $newY++;
        } elseif (Input::isKeyPressed(Input::KEY_LEFT) || Input::isKeyPressed(Input::KEY_A)) {
            $newX--;
        } elseif (Input::isKeyPressed(Input::KEY_RIGHT) || Input::isKeyPressed(Input::KEY_D)) {
            $newX++;
        }
        
        // Check collision before moving
        if ($this->maze->isWalkable($newX, $newY)) {
            $this->x = $newX;
            $this->y = $newY;
            $this->moves++;
        }
    }
    
    /**
     * Check if player reached the exit
     */
    public function hasEscaped(): bool
    {
        return $this->maze->isExit($this->x, $this->y);
    }
    
    /**
     * Get player position for rendering
     */
    public function getPosition(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }
    
    public function getSprite(): string
    {
        return $this->sprite;
    }
    
    public function getMoves(): int
    {
        return $this->moves;
    }
    
    /**
     * Get elapsed time since start
     */
    public function getElapsedTime(): float
    {
        return microtime(true) - $this->startTime;
    }
    
    /**
     * Reset player to start
     */
    public function reset(): void
    {
        $start = $this->maze->getStart();
        $this->x = $start['x'];
        $this->y = $start['y'];
        $this->moves = 0;
        $this->startTime = microtime(true);
    }
}
