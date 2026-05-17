<?php

/**
 * Simple Cross-Platform Terminal Maze Game
 * Works on Windows and Unix without external dependencies
 */

class SimpleMazeGame {
    private int $width = 40;
    private int $height = 20;
    private array $maze = [];
    private int $playerX = 1;
    private int $playerY = 1;
    private int $exitX;
    private int $exitY;
    private bool $running = true;
    private int $moves = 0;
    private float $startTime;

    public function __construct() {
        $this->startTime = microtime(true);
        $this->generateMaze();
        $this->placeExit();
    }

    private function generateMaze(): void {
        // Initialize maze with walls
        for ($y = 0; $y < $this->height; $y++) {
            $this->maze[$y] = [];
            for ($x = 0; $x < $this->width; $x++) {
                $this->maze[$y][$x] = '#';
            }
        }

        // Simple maze generation using randomized paths
        $this->carvePath(1, 1);
        
        // Ensure starting position is clear
        $this->maze[1][1] = ' ';
        $this->playerX = 1;
        $this->playerY = 1;
    }

    private function carvePath(int $x, int $y): void {
        $this->maze[$y][$x] = ' ';
        
        $directions = [
            [0, -2], // up
            [0, 2],  // down
            [-2, 0], // left
            [2, 0]   // right
        ];
        
        shuffle($directions);
        
        foreach ($directions as $dir) {
            $newX = $x + $dir[0];
            $newY = $y + $dir[1];
            
            if ($newX > 0 && $newX < $this->width - 1 && 
                $newY > 0 && $newY < $this->height - 1 && 
                $this->maze[$newY][$newX] === '#') {
                
                $this->maze[$y + $dir[1]/2][$x + $dir[0]/2] = ' ';
                $this->carvePath($newX, $newY);
            }
        }
    }

    private function placeExit(): void {
        // Find a clear spot far from start
        do {
            $this->exitX = rand($this->width / 2, $this->width - 2);
            $this->exitY = rand($this->height / 2, $this->height - 2);
        } while ($this->maze[$this->exitY][$this->exitX] === '#');
    }

    private function clearScreen(): void {
        if (PHP_OS_FAMILY === 'Windows') {
            system('cls');
        } else {
            system('clear');
        }
    }

    private function render(): void {
        $this->clearScreen();
        
        echo "╔══════════════════════════════════════════╗\n";
        echo "║           SIMPLE MAZE ESCAPE              ║\n";
        echo "╚══════════════════════════════════════════╝\n\n";
        
        echo "Moves: {$this->moves}  |  Time: " . round(microtime(true) - $this->startTime, 1) . "s\n\n";
        
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                if ($x === $this->playerX && $y === $this->playerY) {
                    echo '@';
                } elseif ($x === $this->exitX && $y === $this->exitY) {
                    echo 'E';
                } else {
                    echo $this->maze[$y][$x];
                }
            }
            echo "\n";
        }
        
        echo "\nControls: WASD or Arrow Keys to move, Q to quit\n";
    }

    private function handleInput(): void {
        // Non-blocking input for Windows
        if (PHP_OS_FAMILY === 'Windows') {
            $input = $this->getWindowsInput();
        } else {
            $input = $this->getUnixInput();
        }
        
        if ($input === null) return;
        
        $newX = $this->playerX;
        $newY = $this->playerY;
        
        switch (strtoupper($input)) {
            case 'W':
            case "\033[A": // Up arrow
                $newY--;
                break;
            case 'S':
            case "\033[B": // Down arrow
                $newY++;
                break;
            case 'A':
            case "\033[D": // Left arrow
                $newX--;
                break;
            case 'D':
            case "\033[C": // Right arrow
                $newX++;
                break;
            case 'Q':
                $this->running = false;
                return;
        }
        
        // Check collision
        if ($newX >= 0 && $newX < $this->width && 
            $newY >= 0 && $newY < $this->height && 
            $this->maze[$newY][$newX] !== '#') {
            
            $this->playerX = $newX;
            $this->playerY = $newY;
            $this->moves++;
            
            // Check win condition
            if ($this->playerX === $this->exitX && $this->playerY === $this->exitY) {
                $this->win();
            }
        }
    }

    private function getWindowsInput(): ?string {
        // Windows non-blocking input
        $input = stream_get_line(STDIN, 1);
        return $input === false ? null : $input;
    }

    private function getUnixInput(): ?string {
        // Unix non-blocking input using stty
        system('stty -icanon -echo');
        $input = fread(STDIN, 1);
        system('stty icanon echo');
        return $input === false ? null : $input;
    }

    private function win(): void {
        $this->clearScreen();
        $time = round(microtime(true) - $this->startTime, 1);
        
        echo "╔══════════════════════════════════════════╗\n";
        echo "║              YOU WIN!                    ║\n";
        echo "╚══════════════════════════════════════════╝\n\n";
        
        echo "Congratulations! You escaped the maze!\n\n";
        echo "Stats:\n";
        echo "  Moves: {$this->moves}\n";
        echo "  Time: {$time}s\n";
        
        $rating = '★★☆';
        if ($this->moves < 50 && $time < 30) {
            $rating = '★★★';
        } elseif ($this->moves < 100 || $time < 60) {
            $rating = '★★☆';
        } else {
            $rating = '★☆☆';
        }
        
        echo "  Rating: {$rating}\n\n";
        echo "Press any key to exit...\n";
        
        if (PHP_OS_FAMILY === 'Windows') {
            fgets(STDIN);
        } else {
            fread(STDIN, 1);
        }
        
        $this->running = false;
    }

    public function run(): void {
        // Set non-blocking mode
        stream_set_blocking(STDIN, false);
        
        while ($this->running) {
            $this->render();
            $this->handleInput();
            usleep(50000); // 50ms delay
        }
        
        // Restore blocking mode
        stream_set_blocking(STDIN, true);
    }
}

// Run the game
$game = new SimpleMazeGame();
$game->run();
