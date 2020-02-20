
<?php


header('Content-type: application/json');



//buscando json
$arquivo = 'https://api.codenation.dev/v1/challenge/dev-ps/generate-data?token=61b6ebfd49c1db63bdf10cc7734bd35ad6dda7db';

//lendo o json
$info = file_get_contents($arquivo);



//faz o parse na string e converte para uma váriavel PHP
$obj = json_decode($info);


$token = $obj->token;


//pega a frase cifrada
$frase = $obj->cifrado; 



$split_frase = str_split($frase);

//var_dump($split_frase);
//pega o numero de deslocamento do json
$n = $obj->numero_casas;

//tamanho do alfabeto na codificação da linguagem
$alfaTamanho = 123;

//caracteres especiais que ficarão de fora
$fora = 97;

//$fraseTeste = 'ola tudo bem meu nome e maria';
//$criptografar = '';
$descriptografada = ' ';

for($i = 0; $i < count($split_frase); $i++){ 
 
  if($split_frase[$i] == '.' ||  $split_frase[$i] == ' '){
      
      $novaFrase =  ord($split_frase[$i]);
      $descriptografada .= chr($novaFrase);
      
  }else{

      $key = ord($split_frase[$i]);

      $novaFrase = $key - $n;

      if($novaFrase >= 0 && $novaFrase < $fora){
        $novaFrase -= $fora;
      }
      
      if($novaFrase < 0){
        $novaFrase = $alfaTamanho + $novaFrase;
      }
      
      $descriptografada .= chr($novaFrase);
      
      
      $x = $descriptografada . '.';
    }
    
  }
  
  var_dump($x);
  $z = $obj->resumo_criptografico;
  
  
  $codificadaNovo = sha1($x);
  
  
  $z = $codificadaNovo ;
  
  $novo_json = [
    "numero_casas" => $n, 
    "token" => $token, 
    "cifrado" => $frase,
    "decifrado" => $x,
    "resumo_criptografico" => $z
  ];
  

      $answer = file_put_contents('answer.json', json_encode($novo_json));

      $url = 'https://api.codenation.dev/v1/challenge/dev-ps/submit-solution?token=61b6ebfd49c1db63bdf10cc7734bd35ad6dda7db';
      $cURL = curl_init($url);


      curl_setopt_array($cURL, [

        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,

          // This is not mandatory, but is a good practice.
        CURLOPT_HTTPHEADER =>
              array(
                    'Content-Type: multipart/form-data'
              ),
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [          
          'answer' => curl_file_create('answer.json')
        
        ], 

        ]);

        
        $response = curl_exec($cURL);
        curl_close($cURL);
        print_r($response)
      

?>
  
    
    



 




