<?php

declare(strict_types=1);

namespace App\Scenes;

use Sendama\Engine\Core\Scene;
use Sendama\Engine\Core\Input;

/**
 * Game Over Scene - Shown when player quits or time runs out
 * 
 * Simple scene offering return to title or retry.
 */
class GameOverScene extends Scene
{
    private string $reason = 'Mission Aborted';
    
    public function awake(): void
    {
        $this->reason = $this->game->getData('gameover_reason') ?? 'Mission Aborted';
    }
    
    public function update(float $deltaTime): void
    {
        if (Input::isKeyPressed(Input::KEY_ENTER) || Input::isKeyPressed(Input::KEY_SPACE)) {
            $this->game->loadScene('game');
        } elseif (Input::isKeyPressed(Input::KEY_Q) || Input::isKeyPressed(Input::KEY_ESCAPE)) {
            $this->game->loadScene('title');
        }
    }
    
    public function render(): void
    {
        $this->clear();
        
        // Game over display
        $this->drawText(25, 5, '╔══════════════════════════════════════════╗');
        $this->drawText(25, 6, '║                                          ║');
        $this->drawText(25, 7, '║         M I S S I O N   F A I L E D      ║');
        $this->drawText(25, 8, '║                                          ║');
        $this->drawText(25, 9, '╚══════════════════════════════════════════╝');
        
        // Reason
        $this->drawText(30, 12, $this->reason);
        
        // Options
        $this->drawText(28, 18, '╔══════════════════════════════════╗');
        $this->drawText(28, 19, '║  ENTER = Retry Mission             ║');
        $this->drawText(28, 20, '║  Q/ESC = Return to Command         ║');
        $this->drawText(28, 21, '╚══════════════════════════════════╝');
        
        // Encouragement
        $this->drawText(20, 25, 'The quantum maze awaits your return, navigator...');
    }
}
