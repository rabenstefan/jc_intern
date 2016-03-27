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

    'accepted'             => 'Das Feld :attribute muss akzeptiert werden.',
    'active_url'           => 'Das Feld :attribute ist keine gültige URL.',
    'after'                => 'Das Feld :attribute muss ein Datum nach :date sein.',
    'alpha'                => 'Das Feld :attribute darf nur Buchstaben enthalten.',
    'alpha_dash'           => 'Das Feld :attribute darf nur Buchstaben, Zahlen und Bindestriche enthalten.',
    'alpha_num'            => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array'                => 'Das Feld :attribute muss ein Array sein.',
    'before'               => 'Das Feld :attribute muss ein Datum vor :date sein.',
    'between'              => [
        'numeric' => 'Das Feld :attribute muss zwischen :min und :max liegen.',
        'file'    => 'Das Feld :attribute muss zwischen :min und :max kilobytes groß sein.',
        'string'  => 'Das Feld :attribute muss zwischen :min und :max Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss zwischen :min und :max Elemente enthalten.',
    ],
    'boolean'              => 'Das Feld :attribute muss entweder wahr oder falsch sein.',
    'confirmed'            => 'Das Feld :attribute passt nicht zur Bestätigung.',
    'date'                 => 'Das Feld :attribute hat ein ungültiges Datum.',
    'date_format'          => 'Das Feld :attribute passt nicht zum Format :format.',
    'different'            => 'Das Feld :attribute und :other müssen unterschiedlich sein.',
    'digits'               => 'Das Feld :attribute muss :digits Zeichen lang sein.',
    'digits_between'       => 'Das Feld :attribute muss zwischen :min und :max viele Zeichen haben.',
    'distinct'             => 'Das Feld :attribute darf noch nicht existieren.',
    'email'                => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'exists'               => 'Das ausgewählte Feld :attribute ist ungültig.',
    'filled'               => 'Das Feld :attribute muss ausgefüllt werden.',
    'image'                => 'Das Feld :attribute muss ein Bild sein.',
    'in'                   => 'Das ausgewählte Feld :attribute ist ungültig.',
    'in_array'             => 'Das Feld :attribute ist in :other nicht vorhanden.',
    'integer'              => 'Das Feld :attribute muss eine ganze Zahl sein.',
    'ip'                   => 'Das Feld :attribute muss eine gültige IP-Adresse sein.',
    'json'                 => 'Das Feld :attribute muss ein gültiger JSON-Ausdruck sein.',
    'max'                  => [
        'numeric' => 'Das Feld :attribute darf nicht größer als :max sein.',
        'file'    => 'Das Feld :attribute darf nicht größer als :max kilobytes sein.',
        'string'  => 'Das Feld :attribute darf nicht länger als :max Zeichen sein.',
        'array'   => 'Das Feld :attribute darf nicht mehr als :max Elemente enthalten.',
    ],
    'mimes'                => 'Das Feld :attribute muss vom Typ :values sein.',
    'min'                  => [
        'numeric' => 'Das Feld :attribute darf nicht kleiner als :min sein.',
        'file'    => 'Das Feld :attribute darf nicht kleiner als :min kilobytes sein.',
        'string'  => 'Das Feld :attribute darf nicht kürzer als :min Zeichen sein.',
        'array'   => 'Das Feld :attribute darf nicht weniger als :min Elemente enthalten.',
    ],
    'not_in'               => 'Das ausgewählte Feld :attribute ist ungültig.',
    'numeric'              => 'Das Feld :attribute muss eine Zahl sein.',
    'present'              => 'Das Feld :attribute muss vorhanden sein.',
    'regex'                => 'Das Feld :attribute hat das falsche Format.',
    'required'             => 'Das Feld :attribute ist Pflicht.',
    'required_if'          => 'Das Feld :attribute ist Pflicht, wenn :other den Wert :value hat.',
    'required_unless'      => 'Das Feld :attribute ist Pflicht, außer :other liegt in :values.',
    'required_with'        => 'Das Feld :attribute ist Pflicht, wenn :values ausgefüllt ist.',
    'required_with_all'    => 'Das Feld :attribute ist Pflicht, wenn :values ausgefüllt sind.',
    'required_without'     => 'Das Feld :attribute ist Pflicht, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das Feld :attribute ist Pflicht, wenn keine der Werte :values vorhanden sind.',
    'same'                 => 'Das Feld :attribute und :other müssen überein stimmen.',
    'size'                 => [
        'numeric' => 'Das Feld :attribute muss :size sein.',
        'file'    => 'Das Feld :attribute muss :size kilobytes groß sein.',
        'string'  => 'Das Feld :attribute muss :size Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss genau :size Elemente enthalten.',
    ],
    'string'               => 'Das Feld :attribute muss eine Zeichenkette sein.',
    'timezone'             => 'Das Feld :attribute muss eine gültige Zeitzone sein.',
    'unique'               => 'Das Feld :attribute wurde bereits verwendet.',
    'url'                  => 'Das Feld :attribute hat das falsche Format.',

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
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'first_name'    => 'Vorname',
        'last_name'     => 'Nachname',
        'email'         => 'E-Mail-Adresse',
        'birthday'      => 'Geburtstag',
        'phone'         => 'Telefonnummer',
        'address_street'=> 'Straße',
        'address_zip'   => 'PLZ',
        'address_city'  => 'Stadt',
        'sheet_deposit_returned' => 'Notenpfand zurückgegeben',
        'label'         => 'Beschreibung',
        'can_plan_rehearsal' => 'Proben anlegen',
        'can_plan_gig'  => 'Auftritte anlegen',
        'can_send_mail' => 'Mails verschicken',
        'can_configure_system' => 'Admin',
        'only_own_voice'=> 'Nur eigene Stimme',
    ],

];
