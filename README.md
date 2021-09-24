# wp-taxonomy

```php
$builder = new HAMWORKS\WP\Taxonomy\Builder( 'slug', 'name', [ 'post' ] );
$builder->set_options( 
    [
        'public'      => true,
        'description' => '',
        'has_archive' => true,
    ]
);
$builder->create();
```

## Set initial term.

```php
$builder = new HAMWORKS\WP\Taxonomy\Builder( 'slug', 'name', [ 'post' ] );
$builder->set_options( 
    [
        'public'      => true,
        'description' => '',
        'has_archive' => true,
    ]
);
$your_term = new \HAMWORKS\WP\Taxonomy\Term( 'name', 'slug' );
$builder->set_initial_term( $your_term );
$builder->create();
```
