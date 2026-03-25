# API Validation Checklist

**Status:** Cannot access live Sendama Engine source — these assumptions need verification

## Critical API Assumptions

### 1. Scene Base Class
**Our code assumes:**
```php
use Sendama\Engine\Core\Scene;
class TitleScene extends Scene
```

**To verify:**
- [ ] Does `Sendama\Engine\Core\Scene` exist?
- [ ] Does it have `awake()`, `update($deltaTime)`, `render()` methods?
- [ ] Does it provide `drawText($x, $y, $text)` helper?
- [ ] Does it provide `clear()` method?

**Check in:** `vendor/sendamaphp/engine/src/Core/Scene.php`

---

### 2. Input Class
**Our code assumes:**
```php
use Sendama\Engine\Core\Input;
Input::isKeyPressed(Input::KEY_UP)
Input::isKeyPressed(Input::KEY_ENTER)
Input::KEY_W, Input::KEY_A, Input::KEY_S, Input::KEY_D
```

**To verify:**
- [ ] Does `Sendama\Engine\Core\Input` exist?
- [ ] Does it have `isKeyPressed($key)` static method?
- [ ] Are key constants defined: `KEY_UP`, `KEY_DOWN`, `KEY_LEFT`, `KEY_RIGHT`?
- [ ] Are key constants defined: `KEY_W`, `KEY_A`, `KEY_S`, `KEY_D`?
- [ ] Are key constants defined: `KEY_ENTER`, `KEY_SPACE`, `KEY_ESCAPE`, `KEY_Q`?

**Check in:** `vendor/sendamaphp/engine/src/Core/Input.php`

---

### 3. Game Class
**Our code assumes:**
```php
use Sendama\Engine\Core\Game;
$game = new Game($name, $width, $height, $fps);
$game->addScene($name, $scene);
$game->loadScene($name);
$game->run();
$game->quit();
$game->setData($key, $value);
$game->getData($key);
```

**To verify:**
- [ ] Does `Sendama\Engine\Core\Game` exist?
- [ ] Does constructor accept `($name, $width, $height, $fps)`?
- [ ] Does it have `addScene($name, Scene $scene)`?
- [ ] Does it have `loadScene($name)`?
- [ ] Does it have `run()` method?
- [ ] Does it have `quit()` method?
- [ ] Does it have `setData($key, $value)` / `getData($key)`?

**Check in:** `vendor/sendamaphp/engine/src/Core/Game.php`

---

### 4. Scene → Game Reference
**Our code assumes:**
```php
$this->game  // Available in Scene subclasses
```

**To verify:**
- [ ] Does Scene base class provide `$this->game` property?

---

### 5. UI Menu Classes
**Our code assumes:**
```php
use Sendama\Engine\UI\Menu;
use Sendama\Engine\UI\MenuItem;
$menu = new Menu([new MenuItem($id, $label, $callback)]);
$menu->previous();
$menu->next();
$menu->select();
$menu->render($x, $y);
```

**To verify:**
- [ ] Does `Sendama\Engine\UI\Menu` exist?
- [ ] Does `Sendama\Engine\UI\MenuItem` exist?
- [ ] Does Menu accept array of MenuItems in constructor?
- [ ] Does it have `previous()`, `next()`, `select()` methods?
- [ ] Does it have `render($x, $y)` method?

**Check in:** `vendor/sendamaphp/engine/src/UI/`

---

## If APIs Don't Match

### Option 1: Adjust our code
Update class names, method names, or signatures to match actual API.

### Option 2: Create minimal wrappers
If Sendama API differs significantly, create adapter classes that translate our clean structure to their API.

### Option 3: Simplify
Remove Menu UI if not available — use direct keyboard handling instead.

---

## Quick Test Script

Create `test_api.php` in the project root:

```php
<?php
require_once 'vendor/autoload.php';

echo "Testing Sendama API...\n\n";

// Test 1: Scene class
if (class_exists('Sendama\Engine\Core\Scene')) {
    echo "✓ Scene class exists\n";
} else {
    echo "✗ Scene class NOT FOUND\n";
}

// Test 2: Input class
if (class_exists('Sendama\Engine\Core\Input')) {
    echo "✓ Input class exists\n";
    $reflection = new ReflectionClass('Sendama\Engine\Core\Input');
    echo "  Methods: " . implode(', ', $reflection->getMethodNames()) . "\n";
} else {
    echo "✗ Input class NOT FOUND\n";
}

// Test 3: Game class
if (class_exists('Sendama\Engine\Core\Game')) {
    echo "✓ Game class exists\n";
} else {
    echo "✗ Game class NOT FOUND\n";
}

// Test 4: Menu classes
if (class_exists('Sendama\Engine\UI\Menu')) {
    echo "✓ Menu class exists\n";
} else {
    echo "✗ Menu class NOT FOUND\n";
}

if (class_exists('Sendama\Engine\UI\MenuItem')) {
    echo "✓ MenuItem class exists\n";
} else {
    echo "✗ MenuItem class NOT FOUND\n";
}

echo "\nDone.\n";
```

Run: `php test_api.php`

---

## Example Game Reference

Compare with existing working examples:
- `vendor/sendamaphp/example-game-break-out/src/Scenes/`
- `vendor/sendamaphp/example-game-hangman/src/Scenes/`
- `vendor/sendamaphp/example-game-the-collector/src/Scenes/`

Look for patterns in how they:
1. Extend Scene
2. Handle input
3. Transition between scenes
4. Render text/graphics
