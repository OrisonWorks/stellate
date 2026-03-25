# Stellate

A terminal-based space maze escape game built with [Sendama](https://github.com/sendamaphp/engine) — an official example project for the Sendama 2D Game Engine.

![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue)
![License](https://img.shields.io/badge/license-MIT-green)

## Overview

**Stellate** puts you in the role of a quantum navigator lost in unstable dimensional mazes. Navigate procedurally generated labyrinths, find the exit portal (◈), and escape back to normal space.

This project demonstrates:
- **Scene management** — Title, Game, Victory, and Game Over screens
- **Entity-component pattern** — Player controller with collision detection
- **Procedural generation** — Recursive backtracking maze algorithm
- **Input handling** — Keyboard controls with arrow keys and WASD
- **Game state** — Win conditions, stats tracking, pause functionality

## Quick Start

### Prerequisites

- PHP 8.1 or higher
- Composer

### Installation

```bash
# Clone the repository
git clone https://github.com/OrisonWorks/stellate.git
cd stellate

# Install dependencies
composer install

# Run the game
php index.php
```

Or use Sendama CLI:

```bash
# Install Sendama CLI globally
composer global require sendamaphp/console

# Run the game
sendama run
```

## How to Play

| Key | Action |
|-----|--------|
| `↑` `↓` `←` `→` | Navigate menus |
| `W` `A` `S` `D` | Move player in game |
| `Enter` / `Space` | Select / Confirm |
| `P` / `Esc` | Pause game |
| `Q` | Quit to menu |

### Objective

Navigate the maze from the starting position (☉) to the exit portal (◈). The maze is procedurally generated — each playthrough is unique.

### Scoring

Your performance is rated based on:
- **Moves** — Fewer is better
- **Time** — Faster is better

Ratings:
- ★★★ Quantum Navigator (Elite)
- ★★☆ Space Explorer (Skilled)
- ★☆☆ Cadet (Training)

## Project Structure

```
stellate/
├── src/
│   ├── Scenes/
│   │   ├── TitleScene.php      # Main menu and game intro
│   │   ├── GameScene.php       # Core gameplay
│   │   ├── VictoryScene.php    # Win screen with stats
│   │   └── GameOverScene.php   # Retry/quit screen
│   ├── Entities/
│   │   └── Player.php          # Player controller
│   └── Game/
│       └── Maze.php            # Maze generator
├── assets/                      # Sprites, sounds, maps
├── config/                      # Configuration files
├── tests/                       # PHPUnit tests
├── .devcontainer/              # VS Code / Codespaces setup
├── composer.json               # PHP dependencies
├── sendama.json                # Game configuration
└── index.php                   # Entry point
```

## Learning Path

This example teaches key Sendama concepts:

1. **Scene Lifecycle** (`TitleScene.php`)
   - `awake()` — Initialize scene elements
   - `update()` — Handle input and game logic
   - `render()` — Draw to terminal

2. **Entity Management** (`Player.php`)
   - Position and movement
   - Collision detection with maze walls
   - State tracking (moves, time)

3. **Procedural Content** (`Maze.php`)
   - Recursive backtracking algorithm
   - Grid-based world representation
   - Dynamic generation per game

4. **Game Loop Integration** (`GameScene.php`)
   - Scene transitions
   - Win condition detection
   - HUD rendering

## Configuration

Edit `sendama.json` to customize:

```json
{
  "name": "Stellate",
  "width": 80,        // Terminal width
  "height": 24,       // Terminal height
  "fps": 30,          // Target frame rate
  "scenes": [...]     // Registered scenes
}
```

## Development

### Running Tests

```bash
composer test
```

### Building Assets

```bash
sendama build
```

### Creating Distribution

```bash
sendama export
```

## Architecture Decisions

### Why This Structure?

This example prioritizes **teachability** over complexity:

- **Thin Controllers**: Scenes handle flow, not business logic
- **Separation of Concerns**: Maze generation, player movement, and rendering are isolated
- **Readable First**: Code is optimized for learning, not performance
- **Minimal Dependencies**: Only uses Sendama core + standard PHP

### Maze Algorithm

Uses recursive backtracking for **perfect mazes** — exactly one path between any two points, no loops. This guarantees solvability while maintaining challenge.

### Terminal-First Design

All rendering uses ASCII/Unicode characters for maximum compatibility:
- `█` — Walls
- ` ` — Paths
- `☉` — Player
- `◈` — Exit

## Troubleshooting

### Game won't start

```bash
# Check PHP version
php --version  # Must be 8.1+

# Verify dependencies
composer install
```

### Display issues

Ensure your terminal supports:
- Minimum 80x24 characters
- UTF-8 encoding

### Performance

If the game feels slow:
- Reduce maze size in `GameScene.php`
- Lower FPS in `sendama.json`

## Contributing

This is an official Sendama example. Improvements welcome:

1. Fork the repository
2. Create a feature branch
3. Submit a pull request

Focus areas:
- Additional maze algorithms
- Sound effects
- Color support
- High score persistence

## Resources

- [Sendama Documentation](https://sendama.com/docs)
- [Sendama CLI](https://github.com/sendamaphp/console)
- [Engine Source](https://github.com/sendamaphp/engine)
- [Other Examples](https://github.com/sendamaphp)

## License

MIT — See [LICENSE](LICENSE) for details.

---

Built with ☉ by the SendamaPHP Team
