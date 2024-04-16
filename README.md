# File by Sentgine

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)
[![Latest Stable Version](https://img.shields.io/packagist/v/sentgine/file.svg)](https://packagist.org/sentgine/file)
[![Total Downloads](https://img.shields.io/packagist/dt/sentgine/file.svg)](https://packagist.org/packages/sentgine/file)

File is a PHP library that provides a simple wrapper around file system operations.

## Features

- Create, read, update, and delete files.
- Create directories if they do not exist.
- Replace placeholders in a source file and write the modified content to a destination file.
- Remove directories and all their contents recursively.

## Requirements
- PHP 8.0 or higher.

## Installation

You can install the package via Composer by running the following command:

```bash
composer require sentgine/file:^1.0
```

# Sample Usage of Filesystem

### Basic Usage

```php
use Sentgine\File\Filesystem;

// Create a new instance of the Filesystem class
$fileSystem = new Filesystem();

// Set source and destination files
$fileSystem->setSourceFile('path/to/source/file.txt')
           ->setDestinationFile('path/to/destination/file.txt');

// Read content from the source file
$content = $fileSystem->read();

// Update content in the destination file
$fileSystem->update('New content');

// Delete the destination file
$fileSystem->delete();
```

### Usage of replaceContent

```php
use Sentgine\File\Filesystem;

// Create a new instance of the Filesystem class
$fileSystem = new Filesystem();

 // Get the content of the file.text file
$content = $filesystem->setSourceFile('/source/directory/file.text')->read();

// Create a new directory if it doesn't exist
$filesystem->createDirectory('/source/new_directory');

// Write the content to the Controller file
$filesystem->setDestinationFile('/source/new_directory/new_file.text')->create($content);

// Replace the content of the destination file
$filesystem->replaceContent(
    replacements: [
       'placeholder1' => 'replacement1',
        'placeholder2' => 'replacement2',
    ]
);
```

The destination file (path/to/source/file.txt) should contain placeholders formatted as {{ placeholder }}, as shown in the example below:

```txt
Hello {{ placeholder1 }},

This is a sample file with placeholders. Here is the value of placeholder2: {{ placeholder2 }}.

Thank you!
```

## Changelog
Please see the [CHANGELOG](https://github.com/sentgine/file/blob/main/CHANGELOG.md) file for details on what has changed.

## Security
If you discover any security-related issues, please email sentgine@gmail.com instead of using the issue tracker.

## Credits
**File** is built and maintained by Adrian Navaja.
- Check out some cool tutorials and stuff on [YouTube](https://www.youtube.com/@sentgine)!
- Catch my latest tweets and updates on [Twitter](https://twitter.com/sentgine) (formerly X)!
- Let's connect on a more professional note over on [LinkedIn](https://www.linkedin.com/in/adrian-navaja/)!
- For more information about me and my work, visit my website: [sentgine.com](https://www.sentgine.com/).

## License
The MIT License (MIT). Please see the [LICENSE](https://github.com/sentgine/file/blob/main/LICENSE) file for more information.