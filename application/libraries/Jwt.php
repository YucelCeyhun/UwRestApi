<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Jwt
{
    /**
     * ALG şifreleme algoritmanız
     * KEY sadece sizin bildiğiniz şifreniz
     * EXP yaratılan jwt nin geçerlilik süresi
     */

    private const EXP = 3600;
    private const ALG = "sha256";
    private const KEY = "sifreniz";

    private $HEADER, $PAYLOAD, $VERIFYSIGNATURE;
    private $ISSUER;

    public function __construct()
    {

        $CI = &get_instance();
        $CI->load->helper("jwtformate");
        $this->ISSUER = $_SERVER["SERVER_NAME"];
    }

    private function JwtHeader(&$header)
    {
        $headerArray = (Object)[
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        $headerJson = json_encode($headerArray);

        $header = base64_encode($headerJson);
    }

    /**
     * @param object $payload payload referans değeri
     * @param object $userData  kullanıcı adını,idisini ve yetkiyi içeren bir objedir
     * @param int $time değeri atanamdığında kendine time değeri atayan parametredir
     */
    private function JwtPayload(&$payload, $userData, $time = null)
    {

        $regClaim = [
            "iss" => $this->ISSUER,
            "iat" => $time ? $time : time()
        ];

        $payload = (object)array_merge($regClaim, (array)$userData);
        $payloadJson = json_encode($payload);

        $payload = JwtDataFormate(base64_encode($payloadJson));
    }

    private function JwtVerifySignature($userData, $time = null)
    {
        $this->JwtHeader($this->HEADER);
        $this->JwtPayload($this->PAYLOAD, $userData, $time);

        $headerPayload = JwtHeaderPayload($this->HEADER, $this->PAYLOAD);
        $hash = hash_hmac(self::ALG, $headerPayload, self::KEY);
        return JwtDataFormate(base64_encode($hash));
    }

    public function GetJwt($userData, $time = null)
    {
        $this->VERIFYSIGNATURE = $this->JwtVerifySignature($userData, $time);
        return GetToken($this->HEADER, $this->PAYLOAD, $this->VERIFYSIGNATURE);
    }

    /**
     * jwt değerini alır ve yaratılan jwt ile karşılaştırılır
     * sonuç false dönerse userdan gelen jwt faketir.
     * @param string $jwt kullanıcıdan gelen jwt
     */
    public function CheckJwt($jwt)
    {
        $jwtObj = GetTokenObject($jwt);
        $payload = json_decode(base64_decode($jwtObj->payload));
        $userData = GetUserDataFromPayload($payload);
        $jwtRepeat = $this->GetJwt($userData, $payload->iat);

        if ($payload->iat + self::EXP < time())
            return false;

        return hash_equals($jwtRepeat, $jwt);
    }
}