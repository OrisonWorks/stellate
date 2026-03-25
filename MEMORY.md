# Workspace Memory Bank

**Workspace:** Atatu  
**Project:** Stellate (Sendama Game Engine Example)  
**Created:** March 25, 2026  
**Hackathon:** SendamaPHP / AssegaiPHP Example Projects Hackathon

---

## 1. Hackathon Overview

### Structure
- **Duration:** 2 weeks per project, 4 weeks total
- **Participants:** 3 engineers
- **Prize:** K1000 cash per winning project
- **Projects:** 6 total (3 Sendama games + 3 Assegai APIs)

### Timeline
| Weeks | Project | Type |
|-------|---------|------|
| 1-2 | **Stellate** | Sendama maze escape game |
| 3-4 | **Blog API** | AssegaiPHP CRUD API |

### Deliverables Required
- Working repository with clean code
- README with setup instructions
- Migration files and seed data (for APIs)
- Screenshots (setup, docs, happy path)
- 5-minute demo walkthrough

---

## 2. Stellate Project

### Concept
**Stellate** — A terminal-based space maze escape game built with Sendama. Player navigates procedurally generated quantum mazes from start (☉) to exit (◈).

### Repository
- **URL:** https://github.com/OrisonWorks/stellate
- **Original:** https://github.com/sendamaphp/stellate (moved to OrisonWorks)
- **License:** MIT
- **Visibility:** Public

### Project Structure
```
src/
├── Scenes/
│   ├── TitleScene.php       # Menu, intro, scene transitions
│   ├── GameScene.php        # Core gameplay, HUD, pause
│   ├── VictoryScene.php     # Win screen, stats, rating
│   └── GameOverScene.php    # Fail state, retry options
├── Entities/
│   └── Player.php           # Movement, collision, state tracking
└── Game/
    └── Maze.php             # Procedural generation, rendering

Configuration:
├── composer.json            # Dependencies, autoloading
├── sendama.json             # Game config (80x24, 30fps)
├── index.php                # Entry point
└── .devcontainer/           # VS Code / Codespaces setup

Documentation:
├── README.md                # Full documentation
├── SPEC.md                  # Mini-specification (Day 2)
└── API_VALIDATION.md        # API assumptions checklist
```

### Game Mechanics

**Maze Generation:**
- Algorithm: Recursive backtracking (perfect maze)
- Size: 41x21 cells (odd numbers required)
- Walls: █ (full block)
- Paths: " " (space)
- Start: (1, 1)
- Exit: (39, 19)

**Player:**
- Sprite: ☉ (sun symbol)
- Controls: WASD or Arrow keys
- Collision detection with walls
- Move counter and timer tracking

**Win Condition:**
- Reach exit (◈) at bottom-right
- Stats displayed: moves, time, rating

**Rating System:**
| Rating | Moves | Time | Title |
|--------|-------|------|-------|
| ★★★ | < 50 | < 30s | Quantum Navigator |
| ★★☆ | < 100 | < 60s | Space Explorer |
| ★☆☆ | ≥ 100 | ≥ 60s | Cadet |

### Scenes Flow
```
┌─────────────┐    start     ┌─────────────┐    escape    ┌─────────────┐
│ TitleScene  │ ───────────> │  GameScene  │ ───────────> │ VictoryScene│
│  (menu)     │              │ (gameplay)  │              │  (stats)    │
└─────────────┘              └─────────────┘              └─────────────┘
      ↑                                                          │
      └──────────────────── quit/replay ─────────────────────────┘
```

### Technical Decisions

**Namespace Correction:**
- Initially assumed: `Sendama\Engine\Core\Scene`
- Actual location: `Sendama\Engine\Core\Scenes\Scene`
- Fixed in all 4 scene files on March 25, 2026

**Architecture Patterns:**
- Thin controllers: Scenes handle flow, not business logic
- Entity separation: Player logic isolated from Scene
- Pre-rendered maze: Generated once, displayed many times
- Terminal-first: ASCII/Unicode for maximum compatibility

### Dependencies

**composer.json:**
- PHP ≥ 8.1
- sendamaphp/engine ^0.1
- phpunit/phpunit ^10.0 (dev)

**Autoloading:**
- PSR-4: `App\` → `src/`
- PSR-4: `App\Tests\` → `tests/`

---

## 3. Development Environment Challenges

### Constraints
- **Work computer:** Cannot install PHP/Composer locally
- **No admin rights:** Cannot use package managers
- **GitHub Codespaces:** Requires paid plan (unavailable)
- **Gitpod:** Requires credit card verification (unavailable)

### Solution
Local structure development with remote testing:
1. Write complete project structure locally
2. Push to GitHub
3. Clone and test on machine with PHP available
4. Iterate based on test results

### Devcontainer
Created `.devcontainer/devcontainer.json` with:
- PHP 8.3
- Composer
- Sendama CLI (auto-installed via postCreateCommand)
- For future use when Codespaces available

---

## 4. AssegaiPHP Blog API (Weeks 3-4)

### Project Brief
Build an API-only blog/content system demonstrating:
- CRUD operations
- DTO validation
- ORM-backed persistence
- Pagination and filtering
- API documentation and exports

### Planned Modules
- Posts (CRUD, pagination, category filter)
- Categories
- Comments
- Users/Authors

### Planned Endpoints
- `GET /posts` (with pagination)
- `GET /posts/:id`
- `POST /posts` (with DTO validation)
- `PATCH /posts/:id`
- `DELETE /posts/:id`
- `GET /categories`
- `GET /posts/:id/comments`
- `POST /posts/:id/comments`

### Data Model
- Post: id, title, slug, body, status, categoryId, createdAt
- Category: id, name, slug
- Comment: id, postId, authorName, body, createdAt

---

## 5. Key Decisions Log

### March 25, 2026

| Time | Decision | Rationale |
|------|----------|-----------|
| 4:52pm | Join hackathon with 2 projects | Stellate (Sendama) + Blog API (Assegai) |
| 5:03pm | Switch Sendama project to Puzzle | Better for teaching state management |
| 5:25pm | Name game "Stellate" | Space theme, star-shaped, memorable |
| 5:46pm | Create repo on SendamaPHP org | Match existing example naming |
| 6:15pm | Pivot to local development | Codespaces/Gitpod unavailable |
| 7:04pm | Complete project structure | All scenes, entities, game logic |
| 7:37pm | Finish SPEC.md | Day 2 mini-spec complete |
| 7:50pm | Fix Scene namespace | Corrected to Core/Scenes/Scene |

---

## 6. API Assumptions (To Validate)

### Confirmed
- Scene base class exists at `Sendama\Engine\Core\Scenes\Scene`

### Pending Validation
- `Scene::awake()`, `update($deltaTime)`, `render()` methods
- `Scene::drawText($x, $y, $text)` helper
- `Scene::clear()` method
- `Input::isKeyPressed($key)` static method
- Key constants: KEY_UP, KEY_DOWN, KEY_LEFT, KEY_RIGHT, KEY_W, KEY_A, KEY_S, KEY_D
- Key constants: KEY_ENTER, KEY_SPACE, KEY_ESCAPE, KEY_Q
- `Game` class with constructor, addScene, loadScene, run, quit
- `Game::setData()` / `getData()` for scene data passing
- `Menu` and `MenuItem` UI classes

### Validation Method
1. Install dependencies: `composer install`
2. Run API test: `php API_VALIDATION.md` (test script embedded)
3. Compare with existing examples in `vendor/sendamaphp/`

---

## 7. Next Actions

### Immediate (Day 5-6)
- [ ] Validate Stellate runs with actual Sendama engine
- [ ] Fix any API mismatches discovered during testing
- [ ] Add any missing Menu/UI components if not in engine

### Days 6-8 (Feature Complete)
- [ ] Add sound effects (if engine supports)
- [ ] Add color support (ANSI codes)
- [ ] Add difficulty levels
- [ ] Polish pause menu visuals

### Days 9-11 (Documentation)
- [ ] Complete code comments
- [ ] Add walkthrough guide (WALKTHROUGH.md)
- [ ] Record demo script
- [ ] Create screenshots

### Weeks 3-4 (Assegai Blog API)
- [ ] Scaffold Assegai project
- [ ] Implement Posts CRUD
- [ ] Add DTO validation
- [ ] Generate OpenAPI docs
- [ ] Create seed data
- [ ] Write tests

---

## 8. Resources

### Sendama
- Engine: https://github.com/sendamaphp/engine
- CLI: https://github.com/sendamaphp/console
- Website: https://sendama.com

### AssegaiPHP
- Core: https://github.com/assegaiphp/core
- CLI: https://github.com/assegaiphp/console
- Website: https://assegaiphp.com

### Existing Examples
- Break Out: `sendamaphp/example-game-break-out`
- Hangman: `sendamaphp/example-game-hangman`
- The Collector: `sendamaphp/example-game-the-collector`

---

## 9. Contact & Communication

- **Slack channel:** Set up for hackathon (per conversation)
- **Repository:** https://github.com/OrisonWorks/stellate
- **Team:** SendamaPHP Team, AssegaiPHP Team

---

## 10. Notes

### What This Example Teaches
1. **Scene-driven architecture** — Multiple screens with clean transitions
2. **Entity management** — Player controller separated from scene logic
3. **Procedural generation** — Maze algorithm implementation
4. **Input handling** — Keyboard controls in terminal environment
5. **Game state** — Win detection, stats tracking, pause functionality

### Design Philosophy
- Teachability over complexity
- Readable code optimized for learning
- Minimal external dependencies
- Terminal-first for accessibility

### Future Ideas (Post-Hackathon)
- Multiple maze algorithms (Prim's, Kruskal's)
- Collectibles and power-ups
- Timer challenges
- Level progression system
- High score persistence

---

**Last Updated:** March 25, 2026  
**Status:** Day 5 — Prototype complete, pending API validation
