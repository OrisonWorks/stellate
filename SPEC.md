# Stellate - Mini Specification

**Project:** Stellate  
**Type:** Sendama Puzzle Example  
**Hackathon Day:** Day 2 (Mini-Spec Complete)  
**Author:** SendamaPHP Team  
**Version:** 1.0.0

---

## 1. Concept Summary

**Stellate** is a terminal-based space maze escape game where players navigate procedurally generated quantum mazes. The game demonstrates Sendama's scene-driven architecture, entity management, and procedural content generation.

### Core Loop
1. Player selects "Start Mission" from title menu
2. Procedural maze generates (unique each playthrough)
3. Player navigates from start (☉) to exit (◈)
4. Victory screen displays stats and rating
5. Option to replay or return to menu

---

## 2. Learning Objectives

This example teaches new Sendama developers:

### Scene Architecture
- How to structure multiple scenes (Title → Game → Victory/GameOver)
- Scene lifecycle methods (`awake()`, `update()`, `render()`)
- Scene transitions and data passing
- Pause functionality within scenes

### Entity Management
- Player controller with collision detection
- Separation of entity logic from scene logic
- State tracking (position, moves, time)

### Procedural Generation
- Recursive backtracking maze algorithm
- Grid-based world representation
- Dynamic content generation per game session

### Input Handling
- Keyboard input mapping (arrows + WASD)
- Menu navigation vs. gameplay controls
- Multiple input methods (Enter/Space for selection)

### Terminal UI
- ASCII/Unicode character rendering
- HUD design (stats, controls, status messages)
- Box-drawing characters for UI elements

---

## 3. Game Mechanics

### 3.1 Maze Generation

**Algorithm:** Recursive Backtracking (Perfect Maze)
- Guarantees exactly one path between any two points
- No loops or unreachable areas
- Ensures solvability every time

**Parameters:**
- Width: 41 cells (odd number required)
- Height: 21 cells (odd number required)
- Walls: `█` (full block)
- Paths: ` ` (space)
- Start: Top-left (1, 1)
- Exit: Bottom-right (width-2, height-2)

### 3.2 Player Movement

**Controls:**
- Arrow keys or WASD for movement
- One cell per keypress (grid-based)
- Collision detection prevents wall walking
- Move counter increments on successful moves

**Visual:**
- Player sprite: `☉` (sun symbol)
- Exit marker: `◈` (diamond with dot)

### 3.3 Win Condition

**Detection:**
- Check if player position equals exit position
- Trigger on overlap, not proximity

**Victory Stats:**
- Total moves taken
- Elapsed time (seconds)
- Performance rating

### 3.4 Rating System

| Rating | Moves | Time | Title |
|--------|-------|------|-------|
| ★★★ | < 50 | < 30s | Quantum Navigator |
| ★★☆ | < 100 | < 60s | Space Explorer |
| ★☆☆ | ≥ 100 | ≥ 60s | Cadet |

---

## 4. Scene Breakdown

### 4.1 TitleScene

**Purpose:** Entry point, menu navigation, game introduction

**Elements:**
- Title banner with box-drawing characters
- Subtitle: "Navigate the quantum maze. Find the exit."
- Menu with 3 options: Start Mission, How to Play, Exit
- Controls hint at bottom

**Interactions:**
- ↑/↓ Navigate menu
- Enter/Space Select option
- Q Quit game

**Transitions:**
- Start Mission → GameScene
- Exit → Quit application

### 4.2 GameScene

**Purpose:** Core gameplay, maze navigation, win detection

**Elements:**
- Maze render (41x21 grid)
- Player sprite at current position
- Exit marker at goal position
- HUD: moves, time, controls
- Flavor text (rotating quantum-themed messages)
- Pause overlay (when paused)

**State:**
- `paused`: boolean
- `mazeRender`: pre-rendered maze strings
- `statusMessage`: temporary notifications
- `statusTimer`: message duration

**Interactions:**
- WASD/Arrows: Move player
- P/Esc: Toggle pause
- Q: Quit to title

**Transitions:**
- Player reaches exit → VictoryScene (with stats)
- Q pressed → TitleScene

### 4.3 VictoryScene

**Purpose:** Celebrate completion, display stats, offer replay

**Elements:**
- "ESCAPE COMPLETE" banner
- Flavor text about dimensional coordinates
- Stats box: moves, time
- Rating display (stars)
- Control hints

**Transitions:**
- Enter/Space → GameScene (new maze)
- Q/Esc → TitleScene

### 4.4 GameOverScene

**Purpose:** Handle quit/abandon scenarios

**Elements:**
- "MISSION FAILED" banner
- Reason text (customizable)
- Retry/quit options
- Encouragement text

**Transitions:**
- Enter/Space → GameScene
- Q/Esc → TitleScene

---

## 5. File Structure

```
src/
├── Scenes/
│   ├── TitleScene.php       # Menu, intro, navigation
│   ├── GameScene.php        # Gameplay, HUD, pause
│   ├── VictoryScene.php     # Win screen, stats, replay
│   └── GameOverScene.php    # Fail state, retry options
├── Entities/
│   └── Player.php           # Movement, collision, state
└── Game/
    └── Maze.php             # Generation, rendering, queries

config/
└── (future: difficulty levels, themes)

assets/
└── (future: sound effects, extended sprites)

tests/
└── (future: PHPUnit tests for Maze, Player)
```

---

## 6. Class Responsibilities

### Maze
- **Generate:** Create perfect maze via recursive backtracking
- **Render:** Convert grid to terminal-friendly string array
- **Query:** `isWalkable(x, y)`, `isExit(x, y)`, `getStart()`, `getExit()`

### Player
- **Move:** Handle input, check collision, update position
- **Track:** Count moves, measure elapsed time
- **Check:** `hasEscaped()` based on maze exit position

### TitleScene
- **Initialize:** Create menu with callbacks
- **Input:** Navigate and select menu items
- **Render:** Title banner, menu, controls

### GameScene
- **Lifecycle:** Create maze and player on `awake()`
- **Update:** Process input, check win, handle pause
- **Render:** Maze, player, HUD, flavor text, pause overlay

### VictoryScene
- **Display:** Stats passed from GameScene via game data
- **Rate:** Calculate star rating from moves/time
- **Options:** Replay or return to title

---

## 7. Data Flow

### Scene Transitions

```
TitleScene ──start──> GameScene ──escape──> VictoryScene
    ↑                                          │
    └──────────────quit/replay─────────────────┘
```

### Game Data Passing

**Victory Stats:**
- GameScene sets: `$game->setData('victory_stats', [...])`
- VictoryScene gets: `$game->getData('victory_stats')`

**Game Over Reason:**
- Any scene sets reason before transition
- GameOverScene displays custom message

---

## 8. Technical Decisions

### Why Recursive Backtracking?
- **Simple to implement** — ~50 lines of clean code
- **Perfect mazes** — Guaranteed solvability, no dead-ends
- **Fast generation** — Near-instant for 41x21 grids
- **Teachable** — Classic algorithm developers recognize

### Why Entity-Scene Separation?
- **Single responsibility** — Scene handles flow, Player handles movement
- **Reusability** — Player logic could apply to other game types
- **Testability** — Maze and Player testable without Sendama context

### Why Unicode Characters?
- **Visual clarity** — `█` clearer than `#` for walls
- **Thematic fit** — `☉` and `◈` fit space theme
- **Terminal support** — Modern terminals handle UTF-8

### Why Pre-rendered Maze?
- **Performance** — Render once, display many times
- **Separation** — Maze generates data, Scene handles display
- **Extensibility** — Easy to add animations later

---

## 9. Future Extensions

### Near-term (Hackathon scope)
- [ ] Basic sound effects (if Sendama audio ready)
- [ ] Color support (terminal ANSI colors)
- [ ] Difficulty levels (smaller/larger mazes)
- [ ] High score persistence (JSON file)

### Post-hackathon
- [ ] Multiple maze algorithms (Prim's, Kruskal's)
- [ ] Collectibles in maze (optional objectives)
- [ ] Timer challenges (escape before collapse)
- [ ] Level progression (5 mazes, increasing size)

---

## 10. Acceptance Criteria

**Done means:**

- [ ] Game launches without errors via `php index.php`
- [ ] Title menu navigable with ↑↓, selectable with Enter
- [ ] Maze generates uniquely each playthrough
- [ ] Player moves with WASD or arrows, collides with walls
- [ ] Exit detection triggers Victory scene
- [ ] Victory displays correct moves, time, and rating
- [ ] Replay starts new maze from Title or Victory
- [ ] Quit returns to Title or exits cleanly
- [ ] Pause (P/Esc) stops gameplay, shows overlay
- [ ] README explains how to run and what files teach

---

## 11. Demo Path (5 minutes)

1. **Launch** game, show title screen (10s)
2. **Explain** project structure, open 2-3 key files (60s)
3. **Play** through maze, highlight movement and collision (90s)
4. **Reach** exit, show Victory screen with stats (30s)
5. **Replay** showing different maze generation (30s)
6. **Review** learning objectives, point to documentation (60s)

---

**Status:** SPEC COMPLETE — Ready for Days 3-4 Prototype
