<?php

namespace Tests\Unit\Rules;

use App\Rules\NoSpecialCharacters;
use PHPUnit\Framework\TestCase;

class NoSpecialCharactersTest extends TestCase
{
    private function validate(string $value): ?string
    {
        $message = null;
        $rule = new NoSpecialCharacters;
        $rule->validate('campo', $value, function (string $msg) use (&$message) {
            $message = $msg;
        });

        return $message;
    }

    // --- valid inputs ---

    public function test_passes_plain_text(): void
    {
        $this->assertNull($this->validate('Bob Dylan'));
    }

    public function test_passes_with_apostrophe(): void
    {
        $this->assertNull($this->validate("O'Brien"));
    }

    public function test_passes_with_accented_characters(): void
    {
        $this->assertNull($this->validate('Björk Guðmundsdóttir'));
    }

    public function test_passes_with_internal_spaces(): void
    {
        $this->assertNull($this->validate('Calle Mayor 12'));
    }

    public function test_passes_with_hyphen(): void
    {
        $this->assertNull($this->validate('AC/DC'));
    }

    // --- whitespace ---

    public function test_fails_with_leading_whitespace(): void
    {
        $this->assertStringContainsString('espacios en blanco', $this->validate(' Bob'));
    }

    public function test_fails_with_trailing_whitespace(): void
    {
        $this->assertStringContainsString('espacios en blanco', $this->validate('Bob '));
    }

    public function test_fails_with_only_whitespace(): void
    {
        $this->assertStringContainsString('espacios en blanco', $this->validate('   '));
    }

    // --- special characters ---

    public function test_fails_with_asterisk(): void
    {
        $this->assertStringContainsString('no permitidos', $this->validate('test*'));
    }

    public function test_fails_with_angle_brackets(): void
    {
        $this->assertStringContainsString('no permitidos', $this->validate('<script>'));
    }

    public function test_fails_with_curly_braces(): void
    {
        $this->assertStringContainsString('no permitidos', $this->validate('{value}'));
    }

    public function test_fails_with_square_brackets(): void
    {
        $this->assertStringContainsString('no permitidos', $this->validate('[array]'));
    }

    public function test_fails_with_pipe(): void
    {
        $this->assertStringContainsString('no permitidos', $this->validate('a|b'));
    }

    public function test_fails_with_dollar_sign(): void
    {
        $this->assertStringContainsString('no permitidos', $this->validate('$var'));
    }

    public function test_fails_with_percent(): void
    {
        $this->assertStringContainsString('no permitidos', $this->validate('100%'));
    }

    public function test_fails_with_semicolon(): void
    {
        $this->assertStringContainsString('no permitidos', $this->validate('DROP TABLE;'));
    }

    // --- SQL injection patterns ---

    public function test_fails_with_sql_inline_comment(): void
    {
        $this->assertStringContainsString('no permitidas', $this->validate("value -- comment"));
    }

    public function test_fails_with_sql_block_comment_open(): void
    {
        // /* contains * which is caught by the special chars check first
        $this->assertStringContainsString('no permitidos', $this->validate('value /* comment'));
    }

    public function test_fails_with_sql_block_comment_close(): void
    {
        // */ contains * which is caught by the special chars check first
        $this->assertStringContainsString('no permitidos', $this->validate('comment */ value'));
    }
}
