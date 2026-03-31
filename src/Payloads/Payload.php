<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

use ChukkaWp\ChukkaSpec\Exceptions\InvalidPayloadException;
use Illuminate\Support\Facades\Validator;

abstract class Payload
{
    abstract public function toArray(): array;

    abstract public static function rules(): array;

    public static function fromArray(array $data): static
    {
        $rules = static::rules();

        if (! empty($rules)) {
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                $class = static::class;
                $error = $validator->errors()->first();

                throw new InvalidPayloadException("Invalid payload for {$class}: {$error}");
            }
        }

        return static::hydrate($data);
    }

    abstract protected static function hydrate(array $data): static;
}
