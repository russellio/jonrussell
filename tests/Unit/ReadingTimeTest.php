<?php

use App\Support\ReadingTime;

test('average word length is computed for a normal body', function () {
    expect(ReadingTime::averageWordLength('<p>abcd ef</p>'))->toBe(3);
});

test('average word length is null for an image-only body', function () {
    expect(ReadingTime::averageWordLength('<p><img src="a.jpg"><img src="b.jpg"></p>'))->toBeNull();
});

test('average word length is null for a whitespace-only body', function () {
    expect(ReadingTime::averageWordLength("<div>  \n </div>"))->toBeNull();
});
