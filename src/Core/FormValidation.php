<?php

namespace App\Core;

/**
 * FormValidation
 *
 * A robust form validation helper for verifying input data against a set of rules.
 */
class FormValidation
{
    /**
     * @var array The data to be validated.
     */
    protected array $data = [];

    /**
     * @var array Validation error messages.
     */
    protected array $errors = [];

    /**
     * @var array Parsed rules for each field.
     */
    protected array $parsedRules = [];

    /**
     * Constructor.
     *
     * @param array $data Data to validate.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Factory method to create an instance and validate data.
     *
     * @param array $data Input data.
     * @param array $rules Validation rules.
     * @return self
     */
    public static function validate(array $data, array $rules): self
    {
        $instance = new self($data);
        $instance->applyRules($rules);
        return $instance;
    }

    /**
     * Apply an array of rules to the data.
     *
     * @param array $rules Rules in the format ['field' => 'rule1|rule2:param']
     * @return void
     */
    public function applyRules(array $rules): void
    {
        // Parse rules
        foreach ($rules as $field => $fieldRules) {
            $rulesArray = is_string($fieldRules) ? explode('|', $fieldRules) : (array) $fieldRules;
            $this->parsedRules[$field] = [];

            foreach ($rulesArray as $rule) {
                $ruleName = $rule;
                $ruleValue = null;

                if (str_contains($rule, ':')) {
                    [$ruleName, $ruleValue] = explode(':', $rule, 2);
                }

                $this->parsedRules[$field][trim($ruleName)] = $ruleValue !== null ? trim($ruleValue) : null;
            }
        }

        // Apply parsed rules
        foreach ($this->parsedRules as $field => $fieldRules) {
            foreach ($fieldRules as $ruleName => $ruleValue) {
                $this->applyRule($field, $ruleName, $ruleValue);
            }
        }
    }

    /**
     * Check if a field is designated as numeric by its rules.
     *
     * @param string $field
     * @return bool
     */
    protected function isNumericField(string $field): bool
    {
        return array_key_exists('numeric', $this->parsedRules[$field] ?? []);
    }

    /**
     * Apply a single rule to a specific field.
     *
     * @param string $field
     * @param string $ruleName
     * @param string|null $ruleValue
     * @return void
     */
    protected function applyRule(string $field, string $ruleName, ?string $ruleValue): void
    {
        $value = $this->data[$field] ?? null;

        // If field is not present/empty and rule is not required, skip validation
        if ($ruleName !== 'required' && ($value === null || $value === '')) {
            return;
        }

        switch ($ruleName) {
            case 'required':
                if ($value === null || (is_string($value) && trim($value) === '') || (is_array($value) && empty($value))) {
                    $this->addError($field, "The {$field} field is required.");
                }
                break;

            case 'email':
                if (!is_scalar($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "The {$field} must be a valid email address.");
                }
                break;

            case 'min':
                if ($this->isNumericField($field)) {
                    if (!is_scalar($value) || (is_numeric($value) && $value < (float) $ruleValue)) {
                        $this->addError($field, "The {$field} must be at least {$ruleValue}.");
                    }
                } else {
                    if (!is_scalar($value)) {
                        $this->addError($field, "The {$field} must be at least {$ruleValue} characters.");
                    } else {
                        $length = function_exists('mb_strlen') ? mb_strlen((string)$value) : strlen((string)$value);
                        if ($length < (int) $ruleValue) {
                            $this->addError($field, "The {$field} must be at least {$ruleValue} characters.");
                        }
                    }
                }
                break;

            case 'max':
                if ($this->isNumericField($field)) {
                    if (!is_scalar($value) || (is_numeric($value) && $value > (float) $ruleValue)) {
                        $this->addError($field, "The {$field} must not be greater than {$ruleValue}.");
                    }
                } else {
                    if (!is_scalar($value)) {
                        $this->addError($field, "The {$field} must not be greater than {$ruleValue} characters.");
                    } else {
                        $length = function_exists('mb_strlen') ? mb_strlen((string)$value) : strlen((string)$value);
                        if ($length > (int) $ruleValue) {
                            $this->addError($field, "The {$field} must not be greater than {$ruleValue} characters.");
                        }
                    }
                }
                break;

            case 'numeric':
                if (!is_scalar($value) || !is_numeric($value)) {
                    $this->addError($field, "The {$field} must be a number.");
                }
                break;

            case 'alpha':
                if (!is_string($value) || !ctype_alpha($value)) {
                    $this->addError($field, "The {$field} must only contain letters.");
                }
                break;

            case 'alphanumeric':
                if ((!is_string($value) && !is_int($value)) || !ctype_alnum((string)$value)) {
                    $this->addError($field, "The {$field} must only contain letters and numbers.");
                }
                break;

            case 'match':
                $matchValue = $this->data[$ruleValue] ?? null;
                if ($value !== $matchValue) {
                    $this->addError($field, "The {$field} must match the {$ruleValue} field.");
                }
                break;
        }
    }

    /**
     * Add an error message for a field.
     *
     * @param string $field
     * @param string $message
     * @return void
     */
    protected function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        // Prevent duplicate messages
        if (!in_array($message, $this->errors[$field], true)) {
            $this->errors[$field][] = $message;
        }
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
     * Check if validation failed.
     *
     * @return bool
     */
    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * Get all validation errors.
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get the first validation error for a given field.
     *
     * @param string $field
     * @return string|null
     */
    public function first(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }
}
