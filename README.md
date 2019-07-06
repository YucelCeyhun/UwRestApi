# UW Php Rest Api #

## Genel Açıklama Listesi ##
- Api Codeigniter altında geliştirlmiştir.
- JWT cookie sisteminde tutulabildiği gibi parametre olarak alınabilir,
- Token süresi 60 dakida olarak ayarlanmıştır.JWT.php altında tekrar ayarlanabilir,
- Her table için rahatlıkla http requestler alınarak response işlemi gerçekleştirilebilir,
- XSS güvenliği config.php üzerinde aktif edilebilir

## Token Şifresinin Belirlenmesi ##

Library/Jwt.php daki KEY sabitini değiştirerek şifre belirleyebilirsiniz;
Users.php;
```php
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
    private const KEY = "SIFRE";

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
```

Bu işlemden  sonra **localhost/token** adresinden yeni token oluşturabilirsiniz.

## Fark Tablolarda Http Request-Response ##
Controller dizini altında yeni bir Users.php dosyası oluşturuyoruz;
```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

    /**
     * Hangi http eylemleri yapılacaksa o işimde method tanımlanır
     * methodun gövdesine gene aynı adı tasıyan,libraryden method çekilir; $this->responsebody->Get($id);
     * $id sayfadaki id yi temsil eder buda tablo idsine karşılık gelir
     */

class Subject extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $config["table"] = "users"; //sql tablo ismini belirleyin
        $this->load->library("responsebody", $config);
    }

    function GetAll()
    {
        $this->responsebody->GetAll();
    }

    function Get($id)
    {
        $this->responsebody->Get($id);
    }
	
    function Put()
    {
        $data = $this->input->input_stream();
        $this->responsebody->Put($data);
    }
}
```
**localhost/users** adresi üzerinden get request yaparak bütün tablo verisne json formatta erişebilirsiniz. **localhost/users/1** adresi üzerinden idsi 1 olan satıra erişebilirsniz.

## Http Request ile Token a ulaşmak ##
Cookie içeriği olarak oluşturulan token ulaşmak için;
- library/Responseheader.php dosyasındaki **Access-Control-Allow-Origin** header ayarını kendi yer adınıza göre değiştirmelisiniz,
- Http Request işlemi yapılırken async olarak ve Credentials ayarlanarak yapılmalıdır,
- Eğer POST,PUT,PATCH requestleri yapılacaksa header **Content-Type: "application/x-www-form-urlencoded"** şeklinde değiştirilebilir.

Örnek bir put isteği;

```jsx
import React from "react";
import axios from "axios";

class App extends React.Component {
  state = { deneme: null, token: "null" };
  componentDidMount() {
    this.act();
  }

  act = async () => {
    const response = await axios.get(
      "http://localhost/uzmanwebiz/rest/token",{
		  withCredentials:true	
	  }
    );
    this.setState({ deneme: response.data.USER_TOKEN });
  };

  render() {
    return <div>{this.state.deneme}</div>;
  }
}

export default App;
```
