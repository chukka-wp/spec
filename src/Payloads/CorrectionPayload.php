<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class CorrectionPayload extends Payload
{
    public function __construct(
        public readonly string $correctsEventId,
        public readonly string $action,
        public readonly ?string $replacementType,
        public readonly ?array $replacementPayload,
        public readonly ?string $reason,
    ) {}

    public function toArray(): array
    {
        return [
            'corrects_event_id' => $this->correctsEventId,
            'action' => $this->action,
            'replacement_type' => $this->replacementType,
            'replacement_payload' => $this->replacementPayload,
            'reason' => $this->reason,
        ];
    }

    public static function rules(): array
    {
        return [
            'corrects_event_id' => ['required', 'string'],
            'action' => ['required', 'string', 'in:void,replace'],
            'replacement_type' => ['required_if:action,replace', 'nullable', 'string'],
            'replacement_payload' => ['required_if:action,replace', 'nullable', 'array'],
            'reason' => ['nullable', 'string'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            correctsEventId: $data['corrects_event_id'],
            action: $data['action'],
            replacementType: $data['replacement_type'] ?? null,
            replacementPayload: $data['replacement_payload'] ?? null,
            reason: $data['reason'] ?? null,
        );
    }
}
