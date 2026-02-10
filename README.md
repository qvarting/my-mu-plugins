# My MU Plugins

A small collection of personal MU-plugins for WordPress.

## Contents

- **QMD** (`mu-plugins/qmd.php`)  
  Lightweight helper functions for logging data to **Query Monitor** using the
  `qm/debug` hook.

## Installation

1. Make sure WordPress loads MU-plugins from:
   `wp-content/mu-plugins/`

2. Copy the file:
   - `mu-plugins/qmd.php` → `wp-content/mu-plugins/qmd.php`

### Alternative: symlink (local development)

```bash
ln -s /path/to/my-mu-plugins/mu-plugins/qmd.php /path/to/wp/wp-content/mu-plugins/qmd.php
```
