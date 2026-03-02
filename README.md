# My MU Plugins

A small collection of personal MU-plugins for WordPress.

## Samples

```php
// query monitor debug
qmd('åderpåle123');

// var_dump wrapper
dump_var( $user );

// print_r wrapper
r_print( $query, 'Main Query' );

// dump & die
dd( $post );

// include filepath
[include filepath="partials/hero.php"]
[include filepath="partials/hero.php?title=Hello&cta=Buy"]

// simple history logs
sh_log('Import started');
sh_log('Import failed', 'Could not parse CSV', 'error');
sh_log('Debug payload', null, 'debug', ['id' => 123, 'status' => 'ok']);
```
