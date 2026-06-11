<?php

it('escapes html and highlights the keyword in search results', function () {
    $result = highlightMatchedSearch('<img src=x onerror=alert(1)> hello world', 'hello');

    expect($result)->not->toContain('<img')
        ->and($result)->toContain('&lt;img')
        ->and($result)->toContain('<span class="bg-yellow-200">hello</span>');
});

it('returns the escaped text unchanged for a blank keyword', function () {
    expect(highlightMatchedSearch('plain title', ''))->toBe('plain title');
});

it('masks an email without crashing on short local parts', function () {
    expect(hideEmailAddress('ab@example.com'))->toBe('a*@e******.com');
});

it('keeps a multi-label tld when masking an email', function () {
    expect(hideEmailAddress('a@b.co.uk'))->toEndWith('.uk');
});

it('returns an empty string for an invalid email', function () {
    expect(hideEmailAddress('not-an-email'))->toBe('');
});
