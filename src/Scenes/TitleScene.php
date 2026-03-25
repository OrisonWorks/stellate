<?php

declare(strict_types=1);

namespace App\Scenes;

use Sendama\Engine\Core\Scene;
use Sendama\Engine\UI\Menu;
use Sendama\Engine\UI\MenuItem;
use Sendama\Engine\Core\Input;

/**
 * Title Scene - Entry point with menu and game introduction
 * 
 * This scene demonstrates:
 * - Scene structure and lifecycle
 * - Menu creation and navigation
 * - Scene transitions
 */
class TitleScene extends Scene
{
    private Menu $menu;
    
    public function awake(): void
    {
        // Called when scene is first loaded
        $this->menu = new Menu([
            new MenuItem('start', 'Start Mission', [$this, 'startGame']),
            new MenuItem('help', 'How to Play', [$this, 'showHelp']),
            new MenuItem('quit', 'Exit', [$this, 'quit'])
        ]);
    }
    
    public function update(float $deltaTime): void
    {
        // Handle input
        if (Input::isKeyPressed(Input::KEY_UP)) {
            $this->menu->previous();
        }
        if (Input::isKeyPressed(Input::KEY_DOWN)) {
            $this->menu->next();
        }
        if (Input::isKeyPressed(Input::KEY_ENTER) || Input::isKeyPressed(Input::KEY_SPACE)) {
            $this->menu->select();
        }
    }
    
    public function render(): void
    {
        // Clear screen
        $this->clear();
        
        // Draw title banner
        $this->drawText(25, 4, '╔══════════════════════════════════════════╗');
        $this->drawText(25, 5, '║              S T E L L A T E              ║');
        $this->drawText(25, 6, '║         Quantum Maze Escape               ║');
        $this->drawText(25, 7, '╚══════════════════════════════════════════╝');
        
        // Draw subtitle
        $this->drawText(28, 9, 'Navigate the quantum maze. Find the exit.');
        $this->drawText(30, 10, 'Beware of unstable dimensions...');
        
        // Draw menu
        $this->menu->render(32, 14);
        
        // Draw controls hint
        $this->drawText(20, 22, '↑↓ Navigate  |  ENTER Select  |  Q Quit');
    }
    
    public function startGame(): void
    {
        $this->game->loadScene('game');
    }
    
    public function showHelp(): void
    {
        // Could transition to help scene or show modal
        // For now, simple inline help
    }
    
    public function quit(): void
    {
        $this->game->quit();
    }
}
