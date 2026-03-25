<?php

declare(strict_types=1);

namespace App\Game;

/**
 * Maze Generator - Creates procedural quantum mazes
 * 
 * Uses recursive backtracking algorithm to generate perfect mazes
 * (exactly one path between any two points).
 */
class Maze
{
    private int $width;
    private int $height;
    private array $grid;
    
    public function __construct(int $width = 21, int $height = 15)
    {
        // Ensure odd dimensions for proper maze generation
        $this->width = $width % 2 === 0 ? $width + 1 : $width;
        $this->height = $height % 2 === 0 ? $height + 1 : $height;
        $this->grid = [];
    }
    
    /**
     * Generate a new maze using recursive backtracking
     */
    public function generate(): void
    {
        // Initialize grid with walls (1 = wall, 0 = path)
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $this->grid[$y][$x] = 1;
            }
        }
        
        // Start from position (1, 1)
        $this->carve(1, 1);
        
        // Ensure exit exists at bottom-right
        $this->grid[$this->height - 2][$this->width - 2] = 0;
    }
    
    /**
     * Recursive backtracking to carve paths
     */
    private function carve(int $x, int $y): void
    {
        $this->grid[$y][$x] = 0;
        
        // Randomize directions
        $directions = [[0, -2], [2, 0], [0, 2], [-2, 0]];
        shuffle($directions);
        
        foreach ($directions as [$dx, $dy]) {
            $nx = $x + $dx;
            $ny = $y + $dy;
            
            // Check bounds and if cell is unvisited
            if ($nx > 0 && $nx < $this->width - 1 && 
                $ny > 0 && $ny < $this->height - 1 && 
                $this->grid[$ny][$nx] === 1) {
                
                // Carve wall between current and next cell
                $this->grid[$y + $dy / 2][$x + $dx / 2] = 0;
                $this->carve($nx, $ny);
            }
        }
    }
    
    /**
     * Check if position is walkable
     */
    public function isWalkable(int $x, int $y): bool
    {
        if ($x < 0 || $x >= $this->width || $y < 0 || $y >= $this->height) {
            return false;
        }
        return $this->grid[$y][$x] === 0;
    }
    
    /**
     * Check if position is the exit
     */
    public function isExit(int $x, int $y): bool
    {
        return $x === $this->width - 2 && $y === $this->height - 2;
    }
    
    /**
     * Get starting position
     */
    public function getStart(): array
    {
        return ['x' => 1, 'y' => 1];
    }
    
    /**
     * Get exit position
     */
    public function getExit(): array
    {
        return ['x' => $this->width - 2, 'y' => $this->height - 2];
    }
    
    /**
     * Render maze as string array for terminal display
     */
    public function render(): array
    {
        $output = [];
        for ($y = 0; $y < $this->height; $y++) {
            $row = '';
            for ($x = 0; $x < $this->width; $x++) {
                $row .= $this->grid[$y][$x] === 1 ? '█' : ' ';
            }
            $output[] = $row;
        }
        return $output;
    }
    
    public function getWidth(): int
    {
        return $this->width;
    }
    
    public function getHeight(): int
    {
        return $this->height;
    }
}
