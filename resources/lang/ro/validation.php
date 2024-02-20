<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute trebuie să fie acceptat.',
    'accepted_if' => ':attribute trebuie să fie acceptat când :other este :value.',
    'active_url' => ':attribute nu este un URL valid.',
    'after' => ':attribute trebuie să fie o dată după :date.',
    'after_or_equal' => ':attribute trebuie să fie o dată după sau egală cu :date.',
    'alpha' => ':attribute trebuie să conțină doar litere.',
    'alpha_dash' => ':attribute trebuie să conțină doar litere, numere, liniuțe și sublinieri.',
    'alpha_num' => ':attribute trebuie să conțină doar litere și numere.',
    'array' => ':attribute trebuie să fie un array.',
    'before' => ':attribute trebuie să fie o dată înainte de :date.',
    'before_or_equal' => ':attribute trebuie să fie o dată înainte sau egală cu :date.',
    'between' => [
        'numeric' => ':attribute trebuie să fie între :min și :max.',
        'file' => ':attribute trebuie să fie între :min și :max kilobyți.',
        'string' => ':attribute trebuie să fie între :min și :max caractere.',
        'array' => ':attribute trebuie să aibă între :min și :max elemente.',
    ],
    'boolean' => 'Câmpul :attribute trebuie să fie adevărat sau fals.',
    'confirmed' => 'Confirmarea :attribute nu se potrivește.',
    'current_password' => 'Parola este incorectă.',
    'date' => ':attribute nu este o dată validă.',
    'date_equals' => ':attribute trebuie să fie o dată egală cu :date.',
    'date_format' => ':attribute nu se potrivește cu formatul :format.',
    'declined' => ':attribute trebuie să fie declinat.',
    'declined_if' => ':attribute trebuie să fie declinat când :other este :value.',
    'different' => ':attribute și :other trebuie să fie diferite.',
    'digits' => ':attribute trebuie să aibă :digits cifre.',
    'digits_between' => ':attribute trebuie să aibă între :min și :max cifre.',
    'dimensions' => ':attribute are dimensiuni de imagine nevalide.',
    'distinct' => 'Câmpul :attribute are o valoare duplicată.',
    'email' => ':attribute trebuie să fie o adresă de email validă.',
    'ends_with' => ':attribute trebuie să se termine cu una dintre următoarele: :values.',
    'exists' => ':attribute selectat este invalid.',
    'file' => ':attribute trebuie să fie un fișier.',
    'filled' => 'Câmpul :attribute trebuie să aibă o valoare.',
    'gt' => [
        'numeric' => ':attribute trebuie să fie mai mare decât :value.',
        'file' => ':attribute trebuie să fie mai mare decât :value kilobyți.',
        'string' => ':attribute trebuie să fie mai mare de :value caractere.',
        'array' => ':attribute trebuie să aibă mai mult de :value elemente.',
    ],
    'gte' => [
        'numeric' => ':attribute trebuie să fie mai mare sau egal cu :value.',
        'file' => ':attribute trebuie să fie mai mare sau egal cu :value kilobyți.',
        'string' => ':attribute trebuie să fie mai mare sau egal de :value caractere.',
        'array' => ':attribute trebuie să aibă :value elemente sau mai multe.',
    ],
    'image' => ':attribute trebuie să fie o imagine.',
    'in' => ':attribute selectat este invalid.',
    'in_array' => 'Câmpul :attribute nu există în :other.',
    'integer' => ':attribute trebuie să fie un număr întreg.',
    'ip' => ':attribute trebuie să fie o adresă IP validă.',
    'ipv4' => ':attribute trebuie să fie o adresă IPv4 validă.',
    'ipv6' => ':attribute trebuie să fie o adresă IPv6 validă.',
    'json' => ':attribute trebuie să fie un șir JSON valid.',
    'lt' => [
        'numeric' => ':attribute trebuie să fie mai mic decât :value.',
        'file' => ':attribute trebuie să fie mai mic decât :value kilobyți.',
        'string' => ':attribute trebuie să fie mai mic de :value caractere.',
        'array' => ':attribute trebuie să aibă mai puțin de :value elemente.',
    ],
    'lte' => [
        'numeric' => ':attribute trebuie să fie mai mic sau egal cu :value.',
        'file' => ':attribute trebuie să fie mai mic sau egal cu :value kilobyți.',
        'string' => ':attribute trebuie să fie mai mic sau egal de :value caractere.',
        'array' => ':attribute nu trebuie să aibă mai mult de :value elemente.',
    ],
    'max' => [
        'numeric' => ':attribute nu trebuie să fie mai mare decât :max.',
        'file' => ':attribute nu trebuie să fie mai mare de :max kilobyți.',
        'string' => ':attribute nu trebuie să fie mai mare de :max caractere.',
        'array' => ':attribute nu trebuie să aibă mai mult de :max elemente.',
    ],
    'mimes' => ':attribute trebuie să fie un fișier de tipul: :values.',
    'mimetypes' => ':attribute trebuie să fie un fișier de tipul: :values.',
    'min' => [
        'numeric' => ':attribute trebuie să fie cel puțin :min.',
        'file' => ':attribute trebuie să aibă cel puțin :min kilobyți.',
        'string' => ':attribute trebuie să aibă cel puțin :min caractere.',
        'array' => ':attribute trebuie să aibă cel puțin :min elemente.',
    ],
    'multiple_of' => ':attribute trebuie să fie un multiplu al :value.',
    'not_in' => ':attribute selectat este invalid.',
    'not_regex' => 'Formatul :attribute este invalid.',
    'numeric' => ':attribute trebuie să fie un număr.',
    'password' => 'Parola este incorectă.',
    'present' => 'Câmpul :attribute trebuie să fie prezent.',
    'prohibited' => 'Câmpul :attribute este interzis.',
    'prohibited_if' => 'Câmpul :attribute este interzis când :other este :value.',
    'prohibited_unless' => 'Câmpul :attribute este interzis cu excepția cazului în care :other este în :values.',
    'prohibits' => 'Câmpul :attribute interzice :other să fie prezent.',
    'regex' => 'Formatul :attribute este invalid.',
    'required' => 'Câmpul :attribute este obligatoriu.',
    'required_if' => 'Câmpul :attribute este obligatoriu când :other este :value.',
    'required_unless' => 'Câmpul :attribute este obligatoriu cu excepția cazului în care :other este în :values.',
    'required_with' => 'Câmpul :attribute este obligatoriu când :values este prezent.',
    'required_with_all' => 'Câmpul :attribute este obligatoriu când :values sunt prezente.',
    'required_without' => 'Câmpul :attribute este obligatoriu când :values nu este prezent.',
    'required_without_all' => 'Câmpul :attribute este obligatoriu atunci când niciunul dintre :values nu sunt prezente.',
    'same' => ':attribute și :other trebuie să se potrivească.',
    'size' => [
        'numeric' => ':attribute trebuie să fie :size.',
        'file' => ':attribute trebuie să fie :size kilobyți.',
        'string' => ':attribute trebuie să fie :size caractere.',
        'array' => ':attribute trebuie să conțină :size elemente.',
    ],
    'starts_with' => ':attribute trebuie să înceapă cu una dintre următoarele: :values.',
    'string' => ':attribute trebuie să fie un șir de caractere.',
    'timezone' => ':attribute trebuie să fie un fus orar valid.',
    'unique' => ':attribute a fost deja luat.',
    'uploaded' => ':attribute nu a reușit să se încarce.',
    'url' => ':attribute trebuie să fie un URL valid.',
    'uuid' => ':attribute trebuie să fie un UUID valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'mesaj-personalizat',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

