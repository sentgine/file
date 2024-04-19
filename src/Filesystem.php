<?php

namespace Sentgine\File;

use Exception;
use Sentgine\File\Exceptions\FileNotFoundException;

/**
 * Class Filesystem
 *
 * A simple wrapper around file system operations.
 */
class Filesystem
{
    private string $sourceFile;
    private string $destinationFile;

    /**
     * Filesystem constructor.
     */
    public function __construct()
    {
        // Initialize sourceFile and destinationFile as empty strings by default
        $this->sourceFile = '';
        $this->destinationFile = '';
    }

    /**
     * Checks if the file exists.
     *
     * @return bool true if the file exists, false otherwise
     */
    private function fileExists(): bool
    {
        return file_exists($this->destinationFile);
    }

    /**
     * Set the source file.
     *
     * @param string $sourceFile The path to the source file
     *
     * @return Filesystem This Filesystem instance for method chaining
     */
    public function setSourceFile(string $sourceFile): self
    {
        $this->sourceFile = $sourceFile;
        return $this;
    }

    /**
     * Set the destination file.
     *
     * @param string $destinationFile The path to the destination file
     *
     * @return Filesystem This Filesystem instance for method chaining
     */
    public function setDestinationFile(string $destinationFile): self
    {
        $this->destinationFile = $destinationFile;
        return $this;
    }

    /**
     * Creates directories if they do not exist.
     *
     * @param string|string[] $destinationDirectories A single directory path or an array of directory paths
     *
     * @throws Exception if an invalid directory path is provided or if directory creation fails
     */
    public function createDirectory(mixed $destinationDirectories = []): self
    {
        // Convert string input to array if necessary
        if (!is_array($destinationDirectories)) {
            $destinationDirectories = [$destinationDirectories];
        }

        // Validate and create the destination directories
        foreach ($destinationDirectories as $destinationDirectory) {
            // Ensure $destinationDirectory is a non-empty string
            if (!is_string($destinationDirectory) || empty($destinationDirectory)) {
                throw new Exception("Invalid directory path provided: $destinationDirectory");
            }

            // Check if the parent directory exists
            $parentDirectory = dirname($destinationDirectory);
            if (!is_dir($parentDirectory)) {
                throw new Exception("Parent directory does not exist: $parentDirectory");
            }

            // Create the directory if it doesn't already exist
            if (!is_dir($destinationDirectory)) {
                if (!mkdir($destinationDirectory, 0777, true)) {
                    throw new Exception("Failed to create directory: $destinationDirectory");
                }
            }
        }

        return $this;
    }

    /**
     * Creates a new file with the given content.
     *
     * @param string $content The content to write to the file
     *
     * @throws Exception if the file already exists or if unable to write to the file
     */
    public function create(string $content): self
    {
        if ($this->fileExists()) {
            throw new Exception("File ($this->destinationFile) already exists");
        }

        if (file_put_contents($this->destinationFile, $content) === false) {
            throw new Exception("Could not write to file ($this->destinationFile)");
        }

        return $this;
    }

    /**
     * Reads the content of the file.
     *
     * @return string The content of the file
     *
     * @throws FileNotFoundException if the file does not exist
     */
    public function read(): string
    {
        if (!file_exists($this->sourceFile)) {
            throw new FileNotFoundException("File ($this->sourceFile) does not exist");
        }

        return file_get_contents($this->sourceFile);
    }

    /**
     * Updates the content of the file (by overwriting it).
     *
     * @param string $content The new content to write to the file
     *
     * @throws FileNotFoundException if the file does not exist
     * @throws Exception if unable to update the file
     */
    public function update(string $content): self
    {
        if (!$this->fileExists()) {
            throw new FileNotFoundException("File ($this->destinationFile) does not exist");
        }

        if (file_put_contents($this->destinationFile, $content) === false) {
            throw new Exception("Could not update file ($this->destinationFile)");
        }

        return $this;
    }

    /**
     * Deletes the file.
     *
     * @throws FileNotFoundException if the file does not exist
     * @throws Exception if unable to delete the file
     */
    public function delete(): self
    {
        if (!$this->fileExists()) {
            throw new FileNotFoundException("File ($this->destinationFile) does not exist");
        }

        if (!unlink($this->destinationFile)) {
            throw new Exception("Could not delete file ($this->destinationFile)");
        }

        return $this;
    }

    /**
     * Replaces placeholders in a source file and writes the modified content to a destination file.
     *
     * @param array $replacements An associative array of placeholders and their corresponding values
     * @param string $placeholderFormat The format used for placeholders in the source file content (default: '{{ %s }}')
     * @param string|null $sourceFile The path to the source file (default: null, uses the default source file)
     * @param string|null $destinationFile The path to the destination file (default: null, uses the default destination file)
     *
     * @return $this
     * @throws FileNotFoundException if the source file cannot be read
     * @throws Exception if there is an error reading or writing files
     */
    public function replaceContent(array $replacements, string $placeholderFormat = '{{ %s }}', ?string $sourceFile = null, ?string $destinationFile = null): self
    {
        // If source file is not provided, use the default source file
        $sourceFile = $sourceFile ?? $this->sourceFile;

        // Read the source file content
        $content = file_get_contents($sourceFile);

        if ($content === false) {
            throw new FileNotFoundException("Unable to read source file: $sourceFile");
        }

        // Replace the placeholders with the actual values
        foreach ($replacements as $placeholder => $value) {
            $content = str_replace(sprintf($placeholderFormat, $placeholder), $value, $content);
        }

        // If destination file is not provided, use the default destination file
        $destinationFile = $destinationFile ?? $this->destinationFile;

        // Write the new content to the destination file
        if (file_put_contents($destinationFile, $content) === false) {
            throw new Exception("Unable to write to destination file: $destinationFile");
        }

        return $this;
    }

    /**
     * Removes the directory and all its contents recursively.
     *
     * If $directory is not provided, the directory of the source file will be used.
     *
     * @param string|null $directory The directory to remove (default: null, uses the directory of the source file)
     *
     * @return $this
     * @throws Exception If the directory cannot be removed
     */
    public function removeDirectory(?string $directory = null): self
    {
        // If directory is not provided, use the directory of the source file
        $directory = $directory ?? $this->sourceFile;

        // Check if the directory exists
        if (!is_dir($directory)) {
            throw new Exception("Directory ($directory) does not exist");
        }

        // Iterate through each item in the directory
        $objects = scandir($directory);
        foreach ($objects as $object) {
            // Ignore . and ..
            if ($object != "." && $object != "..") {
                $path = $directory . "/" . $object;
                // Remove the item if it's a directory
                if (is_dir($path)) {
                    $this->removeDirectory($path);
                } else {
                    unlink($path); // Remove the item if it's a file
                }
            }
        }

        // Remove the directory itself
        if (!rmdir($directory)) {
            throw new Exception("Failed to remove directory ($directory)");
        }

        return $this;
    }

    /**
     * Copies a file from the source to the destination.
     *
     * @param string|null $sourceFile The path to the source file (default: null, uses the default source file)
     * @param string|null $destinationFile The path to the destination file (default: null, uses the default destination file)
     *
     * @return $this
     * @throws FileNotFoundException if the source file does not exist
     * @throws Exception if unable to copy the file
     */
    public function copy(?string $sourceFile = null, ?string $destinationFile = null): self
    {
        // If source file is not provided, use the default source file
        $sourceFile = $sourceFile ?? $this->sourceFile;

        // If destination file is not provided, use the default destination file
        $destinationFile = $destinationFile ?? $this->destinationFile;

        if (!file_exists($sourceFile)) {
            throw new FileNotFoundException("Source file ($sourceFile) does not exist");
        }

        if (!copy($sourceFile, $destinationFile)) {
            throw new Exception("Failed to copy file from ($sourceFile) to ($destinationFile)");
        }

        return $this;
    }
}
