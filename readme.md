# Aware
Self validating models for Eloquent in L4

[![Build Status](https://travis-ci.org/awareness/aware.png?branch=master)](https://travis-ci.org/awareness/aware)

## Installation

### Composer

```
{ // composer.json
  ...
  "require": {
    "laravel/framework": "4.0.*",
    "awareness/aware": "dev-master"
  },
  ...
}
```

## Usage

Create a model with validation rules:

```php
<?php

use Awareness\Aware\Model;

class User extends Model {

  public static $rules = array(
    'name' => 'required'
  );

}
```

Try to save it:

```php
$user = new User();
$user->save(); // returns false

$user->name = 'Colby';
$user->save(); // saves then returns true!
```

Save without validating:

```php
$user = new User();
$user->force()->save();
```
