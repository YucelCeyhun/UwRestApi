<?php
function StripValue($data)
{
    return strip_tags(trim($data));
}

function StripValues($data)
{
    return array_map("StripValue", $data);
}

/**
 *@param array $validationArray kontrol edilecek form elemanarı için hazırlanan dizidir.
 * Örnek olarak 'username' => ["kullanıcı adı","trim | required"]
 *
 */
function Validation($validationArray)
{
    $CI =& get_instance();

    $CI->form_validation->set_message(
        array(
            "required"      => "{field} Gerekli alanları doldurun",
            "matches"       => "Şifreler uyuşmuyor",
            "is_unique"     => "{field} başka bir kullanıcı tarafından kullanılıyor.",
            "max_length"    => "{field} {param}' dan fazla karakter olamaz",
            "min_length"    => "{field} en az {param} karakter içermelidir.",
            "valid_email"   => "Geçerli bir email adresi giriniz.",
            "numeric"       => "{field} harf veya özel karakter içeremez",
            "less_than_equal_to" => "{field} miktarı {param}' dan fazla olamaz",
            "regex_match"   => "{field} uygun değil"
        )
    );

    foreach ($validationArray as $key => $value){
        $CI->form_validation->set_rules($key, $value[0],$value[1]);
    }
    $result = $CI->form_validation->run();
    return Array("status" => $result,"message" => strip_tags(validation_errors()));

}
