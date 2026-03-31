<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class VarReviewEndPayload extends Payload
{
    public function __construct(
        public readonly string $outcome,
        public readonly ?string $notes,
    ) {}

    public function toArray(): array
    {
        return [
            'outcome' => $this->outcome,
            'notes' => $this->notes,
        ];
    }

    public static function rules(): array
    {
        return [
            'outcome' => ['required', 'string', 'in:goal_confirmed,goal_disallowed,no_change,violent_action_sanctioned'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            outcome: $data['outcome'],
            notes: $data['notes'] ?? null,
        );
    }
}
