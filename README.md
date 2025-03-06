# PHP Dotenv

A simple and lightweight PHP library for loading and managing environment variables from a `.env` file.  
Automatically loads environment variables from `.env` into `getenv()`, `$_ENV`, and `$_SERVER`.

## Installation
To use this package in your PHP project, follow these steps.

1. Add the repository to your `composer.json`
    ```json
    {
        "repositories": {
            "osaru07m/php-dotenv": {
                "type": "vcs",
                "url": "https://github.com/osaru07m/php-dotenv.git",
                "branch": "main"
            }
        }
    }
    ```
2. Require the library using Composer
    ```bash
    composer require osaru07m/php-dotenv
    ```

## Usage
### Loading environment variables
You can load environment variables from a `.env` file using the `Dotenv::load()` method.  
Here's an example of how to load a `.env` file.
```php
use Osaru07m\Dotenv\Dotenv;

// Load environment variables from a .env file
Dotenv::load('/path/to/.env');
```

#### Optional Parameters
The `load()` method accepts two parameters.

| Parameter   | Description  | Required |
|-------------|-------------|:--------:|
| `$filePath` | The path to the environment file. | ✅ |
| `$changeable` | A boolean indicating whether the environment variables can be changed after being set. Default is `false`. | ❌ |

### Getting environment variables
You can retrieve environment variables using the `Dotenv::get()` method.
```php
$value = Dotenv::get('MY_ENV_VARIABLE');
```
If the variable is not set, it will return `null` by default, or you can specify a default value as the second parameter.

```php
$value = Dotenv::get('MY_ENV_VARIABLE', 'default_value');
```

### Get all environment variables
To get all the environment variables, use the `Dotenv::getAll()` method.

```php
$envVariables = Dotenv::getAll();
```

### Adding or updating environment variables
You can add or update environment variables using the `Dotenv::add()` method.

```php
Dotenv::add('MY_NEW_VARIABLE', 'value');
```
If `$changeable` is set to `false` (the default), the value will only be added if it hasn't been set previously.

### Example `.env` File
```env
DB_HOST=localhost
DB_USER=root
DB_PASS=secret
```

### How it works
#### `Dotenv::load()`
Reads the environment variables from the file and sets them in `$_ENV`, `$_SERVER`, and via `putenv()`.

#### `Dotenv::get()`
Retrieves the value of an environment variable from `$_ENV` or `$_SERVER`, with an optional default value.

#### `Dotenv::add()`
Adds or updates an environment variable in `$_ENV`, `$_SERVER`, and the environment (via `putenv()`).

## Contributing
Feel free to fork this repository and submit pull requests. Contributions are welcome!

## License
This library is released under the MIT License. See the [LICENSE.md](./LICENSE.md) file for more details.