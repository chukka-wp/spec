<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class NeutralThrowPayload extends Payload
{
    public function __construct(
        public readonly string $reason,
        public readonly ?string $location,
    ) {}

    public function toArray(): array
    {
        return [
            'reason' => $this->reason,
            'location' => $this->location,
        ];
    }

    public static function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'in:simultaneous_foul,simultaneous_whistle,ball_in_obstruction,disputed_start'],
            'location' => ['nullable', 'string'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            reason: $data['reason'],
            location: $data['location'] ?? null,
        );
    }
}
