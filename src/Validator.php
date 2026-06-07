<?php
declare(strict_types=1);

/**
 * Jednoduchý validátor formulářů s fluent interface.
 * Pro každé pole se uchovává jen první chyba.
 */
final class Validator
{
    /** @var array<string,string> */
    private array $errors = [];

    public function required(string $field, ?string $value, string $message): self
    {
        if ($this->hasError($field)) return $this;
        if ($value === null || trim($value) === '') {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function email(string $field, ?string $value, string $message): self
    {
        if ($this->hasError($field)) return $this;
        if ($value === null || $value === '') return $this;
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function minLength(string $field, ?string $value, int $min, string $message): self
    {
        if ($this->hasError($field)) return $this;
        if ($value === null) return $this;
        if (mb_strlen($value) < $min) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function maxLength(string $field, ?string $value, int $max, string $message): self
    {
        if ($this->hasError($field)) return $this;
        if ($value === null) return $this;
        if (mb_strlen($value) > $max) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function pattern(string $field, ?string $value, string $regex, string $message): self
    {
        if ($this->hasError($field)) return $this;
        if ($value === null || $value === '') return $this;
        if (!preg_match($regex, $value)) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    /**
     * @param array<int,string> $allowed
     */
    public function in(string $field, ?string $value, array $allowed, string $message): self
    {
        if ($this->hasError($field)) return $this;
        if ($value === null || !in_array($value, $allowed, true)) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function isValid(): bool
    {
        return $this->errors === [];
    }

    /** @return array<string,string> */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getError(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }

    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }
}
