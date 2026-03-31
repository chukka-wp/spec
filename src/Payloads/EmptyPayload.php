<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class EmptyPayload extends Payload
{
    public function toArray(): array
    {
        return [];
    }

    public static function rules(): array
    {
        return [];
    }

    protected static function hydrate(array $data): static
    {
        return new self;
    }
}
