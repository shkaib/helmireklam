<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | De following language lines contain De default error messages used by
    | De validator class. Some of Dese rules have multiple versions such
    | as De size rules. Feel free to tweak each of Dese messages here.
    |
    */
    
    'accepted' => 'De :attribute måste accepteras.',
    'active_url' => 'De :attribute är inte en giltig webbadress.',
    'after' => 'De :attribute måste vara ett datum efter :date.',
    'alpha' => 'De :attribute får endast innehålla bokstäver.',
    'alpha_dash' => 'De :attribute får endast innehålla bokstäver, siffror och streck.',
    'alpha_num' => 'De :attribute får endast innehålla bokstäver och siffror.',
    'array' => 'De :attribute måste vara en array.',
    'before' => 'De :attribute måste vara ett datum före :date.',
    'between' => [
        'numeric' => 'De :attribute måste vara mellan :min och :max.',
        'file' => 'De :attribute måste vara mellan :min och :max kilobytes.',
        'string' => 'De :attribute måste vara mellan :min och :max characters.',
        'array' => 'De :attribute måste vara mellan :min och :max items.',
    ],
    'boolean' => 'De :attribute fält måste vara sant eller falskt.',
    'confirmed' => 'De :attribute bekräftelse matchar inte.',
    'date' => 'De :attribute är inte ett giltigt datum.',
    'date_format' => 'De :attribute matchar inte Formatet :format.',
    'different' => 'De :attribute och :oDer måste vara annorlunda.',
    'digits' => 'De :attribute måste vara :digits siffror.',
    'digits_between' => 'De :attribute måste vara mellan :min och :max siffror.',
    'email' => 'De :attribute Måste vara en giltig e-postadress.',
    'exists' => 'De vald :attribute är ogiltig.',
    'filled' => 'De :attribute fältet är obligatoriskt.',
    'image' => 'De :attribute måste vara en bild.',
    'in' => 'De vald :attribute är ogiltig.',
    'integer' => 'De :attribute måste vara ett heltal.',
    'ip' => 'De :attribute måste vara en giltig IP-adress.',
    'json' => 'De :attribute måste vara ett giltigt JSON sträng.',
    'max' => [
        'numeric' => 'De :attribute får inte vara större än :max.',
        'file' => 'De :attribute får inte vara större än :max kilobytes.',
        'string' => 'De :attribute får inte vara större än :max characters.',
        'array' => 'De :attribute får inte ha mer än :max objekt.',
    ],
    'mimes' => 'De :attribute måste vara en fil av type: :values.',
    'min' => [
        'numeric' => 'De :attribute måste vara minst :min.',
        'file' => 'De :attribute måste vara minst :min kilobytes.',
        'string' => 'De :attribute måste vara minst :min characters.',
        'array' => 'De :attribute måste ha åtminstone  :min objekt.',
    ],
    'not_in' => 'De vald :attribute är ogiltig.',
    'numeric' => 'De :attribute måste vara ett tal.',
    'regex' => 'De :attribute formatet är ogiltigt.',
    'required' => 'De :attribute fältet är obligatoriskt.',
    'required_if' => 'De :attribute fält krävs när :oDer är :value.',
    'required_with' => 'De :attribute fält krävs när :values är närvarande.',
    'required_with_all' => 'De :attribute fält krävs när :values är närvarande.',
    'required_without' => 'De :attribute fält krävs när :values inte är närvarande.',
    'required_without_all' => 'De :attribute fält krävs när ingen av :values är närvarande.',
    'same' => 'De :attribute och :oDer måste matcha.',
    'size' => [
        'numeric' => 'De :attribute måste vara :size.',
        'file' => 'De :attribute måste vara :size kilobytes.',
        'string' => 'De :attribute måste vara :size tecken.',
        'array' => 'De :attribute måste innehålla :size objekt.',
    ],
    'string' => 'De :attribute måste vara en sträng.',
    'timezone' => 'De :attribute måste vara en giltig zon.',
    'unique' => 'De :attribute har redan blivit tagen.',
    'url' => 'De :attribute formatet är ogiltigt.',
    
    "recaptcha" => 'De :attribute fält är inte korrekt.',
    
    
    // Blacklist - Whitelist
    'whitelist_email' => 'Den här e-postadressen är svartlistad .',
    'whitelist_domain' => 'De domän din e-postadress är svartlistad .',
    'whitelist_word' => 'De :attribute innehåller en förbjuden ord eller fraser.',
    'whitelist_word_title' => 'De :attribute innehåller en förbjuden ord eller fraser.',
    
    
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using De
    | convention "attribute.rule" to name De lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    
    'custom' => [
        'gender' => [
            'required' => 'Köns krävs.',
            'not_in' => 'Köns krävs.',
        ],
        'first_name' => [
            'required' => 'Ditt förnamn krävs.',
            'min' => 'De :attribute måste vara minst :min tecken.',
            'max' => 'De :attribute får inte vara större än :max tecken.',
        ],
        'last_name' => [
            'required' => 'Ditt efternamn krävs.',
            'min' => 'De :attribute måste vara minst :min tecken.',
            'max' => 'De :attribute får inte vara större än :max tecken.',
        ],
        'user_type' => [
            'required' => 'Typen användaren krävs (Individual eller Professional?).',
            'not_in' => 'Typen användaren krävs (Individual eller Professional?).',
        ],
        'country' => [
            'required' => 'Ditt land krävs.',
            'not_in' => 'Ditt land krävs',
        ],
        'phone' => [
            'required' => 'Telefonnumret krävs.',
            'min' => 'De :attribute måste vara minst :min tecken.',
            'max' => 'De :attribute får inte vara större än :max tecken..',
            'phone_number' => 'De :attribute måste vara ett giltigt telefonnummer.',
        ],
        'email' => [
            'required' => 'Din e-postadress krävs.',
            'email' => 'De :attribute Måste vara en giltig e-postadress.',
            'unique' => 'De :attribute har redan blivit tagen.',
        ],
        'password' => [
            'required' => 'De password field is required.',
            'between' => 'De :attribute must be between :min and :max characters.',
        ],
        'g-recaptcha-response' => [
            'required' => 'Captcha fält är inte korrekt.',
            'recaptcha' => 'Captcha fält är inte korrekt.',
        ],
        'term' => [
            'required' => 'Vous devez lire et accepter les conditions d\'utilisation du site',
            'accepted' => 'Vous devez lire et accepter les conditions d\'utilisation du site',
        ],
        'category' => [
            'required' => 'Den kategori och underkategori krävs.',
            'not_in' => 'Den kategori och underkategori krävs.',
        ],
        'ad_type' => [
            'required' => 'Annonstypen krävs.',
            'not_in' => 'Annonstypen krävs.',
        ],
        'title' => [
            'required' => 'Titeln krävs.',
            'between' => 'De :attribute måste vara mellan :min och:max tecken.',
        ],
        'description' => [
            'required' => 'Beskrivningen krävs.',
            'between' => 'De :attribute måste vara mellan :min och :max characters.',
        ],
        'price' => [
            'required' => 'Priset krävs.',
        ],
        'salary' => [
            'required' => 'Lönen krävs.',
        ],
        'resume' => [
            'required_if' => 'Ditt CV krävs.',
            'mimes' => 'Ditt CV måste i detta formats: :mimes.',
        ],
        'seller_name' => [
            'required' => 'Ditt namn krävs.',
            'min' => 'De :attribute måste vara minst :min tecken.',
            'max' => 'De :attribute får inte vara större än :max tecken.',
        ],
        'seller_email' => [
            'required_without' => 'Din e-postadress krävs.',
            'email' => 'De :attribute Måste vara en giltig e-postadress.',
        ],
        'seller_phone' => [
            'required_without' => 'Telefonnumret krävs.',
            'min' => 'De :attribute måste vara minst :min tecken.',
            'max' => 'De :attribute får inte vara större än :max tecken.',
            'phone_number' => 'Telefonnumret är inte giltigt.',
        ],
        'location' => [
            'required' => 'Regionen krävs.',
            'not_in' => 'Regionen krävs.',
        ],
        'city' => [
            'required' => 'Staden krävs.',
            'not_in' => 'Staden krävs.',
        ],
        'package' => [
            'required_with' => 'Paketet krävs.',
            'not_in' => 'Paketet krävs.',
        ],
        'payment_method' => [
            'required_if' => 'Betalningssätt krävs.',
            'not_in' => 'Betalningssätt krävs.',
        ],
        'sender_name' => [
            'required' => 'Ditt namn krävs.',
            'min' => 'De :attribute måste vara minst :min tecken.',
            'max' => 'De :attribute får inte vara större än :max tecken.',
        ],
        'sender_email' => [
            'required' => 'Din e-postadress krävs.',
            'email' => 'De :attribute Måste vara en giltig e-postadress.',
        ],
        'sender_phone' => [
            'required' => 'Telefonnumret krävs.',
            'min' => 'De :attribute måste vara minst :min tecken.',
            'max' => 'De :attribute får inte vara större än :max tecken.',
            'phone_number' => 'Telefonnumret är inte giltigt.',
        ],
        'subject' => [
            'required' => 'Ämnesområdet krävs.',
            'between' => 'De :attribute måste vara mellan :min och:max tecken.',
        ],
        'message' => [
            'required' => 'Meddelandefältet krävs.',
            'between' => 'De :attribute måste vara mellan :min och:max tecken.',
        ],
        'report_type' => [
            'required' => 'Raison krävs.',
            'not_in' => 'Raison krävs.',
        ],
        'report_sender_email' => [
            'required' => 'Din e-postadress krävs.',
            'email' => 'De :attribute Måste vara en giltig e-postadress.',
        ],
        'report_message' => [
            'required' => 'Meddelandefältet krävs.',
            'between' => 'De :attribute måste vara mellan :min och :max tecken.',
        ],
        'file' => [
            'required' => 'Bilderna fält krävs.',
            'image' => 'De :attribute måste vara bild.',
        ],
        'pictures.*' => [
            'required' => 'Bilderna fält krävs.',
            'image' => 'De :attribute måste vara bild.',
        ],
        'pictures.0' => [
            'required' => 'Bilderna fält krävs.',
            'image' => 'De :attribute måste vara bild.',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | De following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    
    'attributes' => [],

];
