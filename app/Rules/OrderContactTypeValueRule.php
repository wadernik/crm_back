<?php

namespace App\Rules;

use App\Models\Order\Contact\ContactTypeEnum;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use function __;
use function preg_match;
use function strlen;

class OrderContactTypeValueRule implements ValidationRule, DataAwareRule
{
    /**
     * All the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * @param string                                       $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $contacts = [$this->contact()];

        foreach ($contacts as $contact) {
            $contactType = ContactTypeEnum::ids()[$contact['type_id']] ?? null;

            if (!$contactType) {
                $fail(__('order.contact.invalid_type'));
            }

            if ($contactType === ContactTypeEnum::PHONE->value && !preg_match("/^\d{11}$/", $contact['value'])) {
                $fail(__('validation.regex', ['attribute' => __('attributes.order.contacts.phone')]));
            }

            if ($contactType === ContactTypeEnum::SOCIAL->value && strlen($contact['value']) < 3) {
                $fail(__(
                    'validation.gte.string',
                    [
                        'attribute' => __('attributes.order.contacts.social'),
                        'value' => 3,
                    ]
                ));
            }
        }
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array{
     *     type: ContactTypeEnum,
     *     value: string,
     * }
     */
    private function contact(): array
    {
        return $this->data['contact'] ?? [];
    }
}