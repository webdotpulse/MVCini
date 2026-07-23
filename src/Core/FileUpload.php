<?php

namespace App\Core;

/**
 * FileUpload
 *
 * A helper class to streamline the process of handling $_FILES.
 * Includes methods for validating file extensions, MIME types, and max file sizes,
 * securely moving files, and generating unique filenames.
 */
class FileUpload
{
    /**
     * @var array The file data from $_FILES array slice.
     */
    protected array $file;

    /**
     * @var array Allowed file extensions (without dot).
     */
    protected array $allowedExtensions = [];

    /**
     * @var array Allowed MIME types.
     */
    protected array $allowedMimeTypes = [];

    /**
     * @var int Maximum file size in bytes (default: 2MB).
     */
    protected int $maxSize = 2 * 1024 * 1024;

    /**
     * @var array Validation error messages.
     */
    protected array $errors = [];

    /**
     * Constructor.
     *
     * @param array $file A single file array from $_FILES.
     */
    public function __construct(array $file)
    {
        $this->file = $file;
    }

    /**
     * Set the allowed file extensions.
     *
     * @param array $extensions
     * @return self
     */
    public function setAllowedExtensions(array $extensions): self
    {
        $this->allowedExtensions = array_map('strtolower', $extensions);
        return $this;
    }

    /**
     * Set the allowed MIME types.
     *
     * @param array $mimeTypes
     * @return self
     */
    public function setAllowedMimeTypes(array $mimeTypes): self
    {
        $this->allowedMimeTypes = $mimeTypes;
        return $this;
    }

    /**
     * Set the maximum allowed file size in bytes.
     *
     * @param int $sizeBytes
     * @return self
     */
    public function setMaxSize(int $sizeBytes): self
    {
        $this->maxSize = $sizeBytes;
        return $this;
    }

    /**
     * Add an error message.
     *
     * @param string $message
     * @return void
     */
    protected function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    /**
     * Get all validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if validation passed.
     *
     * @return bool
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Validate the file upload against the set rules.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate(): bool
    {
        $this->errors = [];

        // Check if file array is properly formed
        if (!isset($this->file['error']) || is_array($this->file['error'])) {
            $this->addError('Invalid file parameters.');
            return false;
        }

        // Check native PHP upload error codes
        switch ($this->file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->addError('No file sent.');
                return false;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->addError('Exceeded filesize limit.');
                return false;
            default:
                $this->addError('Unknown upload error.');
                return false;
        }

        // Validate file size
        if ($this->file['size'] > $this->maxSize) {
            $this->addError('Exceeded filesize limit of ' . $this->maxSize . ' bytes.');
        }

        // Validate extension if any are specified
        $extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        if (!empty($this->allowedExtensions) && !in_array($extension, $this->allowedExtensions, true)) {
            $this->addError('Invalid file extension.');
        }

        // Validate MIME type if any are specified
        if (!empty($this->allowedMimeTypes)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            if ($finfo === false) {
                $this->addError('Failed to get finfo instance.');
            } else {
                $mimeType = $finfo->file($this->file['tmp_name']);
                if ($mimeType === false || !in_array($mimeType, $this->allowedMimeTypes, true)) {
                    $this->addError('Invalid file format (MIME type).');
                }
            }
        }

        return $this->passes();
    }

    /**
     * Generate a secure unique filename.
     *
     * @param string|null $extension Optional extension to append (without dot). Defaults to original extension.
     * @return string
     */
    public function generateUniqueFilename(?string $extension = null): string
    {
        if ($extension === null) {
            $extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        }

        $uniqueName = bin2hex(random_bytes(16));

        if (!empty($extension)) {
            $uniqueName .= '.' . $extension;
        }

        return $uniqueName;
    }

    /**
     * Validate and securely save the uploaded file to a destination directory.
     *
     * @param string $destinationDir The directory to move the file to.
     * @param string|null $filename Optional filename. If null, a unique one is generated.
     * @return string|false The path to the saved file on success, or false on failure.
     */
    public function save(string $destinationDir, ?string $filename = null): string|false
    {
        if (!$this->validate()) {
            return false;
        }

        // Ensure directory ends with a slash
        $destinationDir = rtrim($destinationDir, '/\\') . DIRECTORY_SEPARATOR;

        // Check if directory exists and is writable
        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0755, true)) {
                $this->addError('Failed to create destination directory.');
                return false;
            }
        } elseif (!is_writable($destinationDir)) {
            $this->addError('Destination directory is not writable.');
            return false;
        }

        if ($filename === null) {
            $filename = $this->generateUniqueFilename();
        } else {
            // Sanitize provided filename
            $filename = basename($filename);
        }

        $destinationPath = $destinationDir . $filename;

        // Move the file securely
        if (move_uploaded_file($this->file['tmp_name'], $destinationPath)) {
            return $destinationPath;
        }

        $this->addError('Failed to move uploaded file.');
        return false;
    }
}
