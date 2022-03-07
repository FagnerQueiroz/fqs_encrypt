<?php
// Está cansado de base64?
// Classe para criptografia de texto
// Gere uma chave privada atravéz do método key_genaration. Chaves diferentes geram textos criptografados diferentes;
// Utilize a mesma chave para o encrypt e decrypt;
// O encode fqs2 gera um texto criptografado maior que o fqs1

/********************************EXEMPLO DE USO ****************************************/
$fqs_encrypt = new fqs_encrypt();
$encoding = 'fqs2';
//$encoding = 'fqs1';

$private_key = $fqs_encrypt->key_genaration();


$private_key = file_get_contents('private_key.txt');
echo $encrypted_text =  $fqs_encrypt->encrypt($private_key,'texto livre',$encoding);
echo '<br>';

$encrypted_text = file_get_contents('encrypted_text.txt');
echo $decrypted_text = $fqs_encrypt->decrypt($private_key,$encrypted_text,$encoding);

/********************************CLASSE fqs_encrypt****************************************/
class fqs_encrypt{
	private	$public_array =['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9'];
	public function key_genaration(){
		$private_key ='';
		$public_key = $this->public_array;
		shuffle($public_key);
		foreach ($public_key as $key => $value) {
			$private_key.= $value.',';
		}
		file_put_contents('private_key.txt',$private_key);
		return $private_key;
	}

	public function encrypt($private_key,$free_text,$encoding){
		$encrypted_text = '';
		if($encoding == 'fqs2'){
			$strengh = 2;
		}else{
			$strengh = 1;
		}
		$free_text = str_split($free_text);
		$private_key=explode(",",$private_key);
		$public_key=$this->public_array;
		$public_key[]='';
		foreach ($free_text as $key => $value) {
			$add_char = '';
			$i = 0;
			while($strengh > $i){
				$char = $public_key[rand(0,61)];
				$add_char.=$char; 
				$i ++;
			}
			$position = array_search($value, $public_key);
			if(in_array($value, $public_key)){
				$encrypted_text.=$private_key[$position]. $add_char; 
			}else{
				$encrypted_text.=$value. $add_char;
			}
		}
		
		file_put_contents('encrypted_text.txt', $encrypted_text);
		return $encrypted_text;
	}

	public function decrypt($private_key,$encrypted_text,$encoding){

		if($encoding == 'fqs2'){
			$count_break = 3;
		}else{
			$count_break = 2;
		}
		$public_key=$this->public_array;
		$public_key[]='';
		$private_key=explode(",",$private_key);
		$result = array_diff($private_key, $public_key);
		$encrypted_text  = str_split($encrypted_text);
		$decrypted_text ='';
		$count = 0;
		$strengh = 3;
		foreach ($encrypted_text as $key => $value) {
			if($count == $count_break){
				$count = 0;
			}
			if($count ==0){
				$count++;
				if(in_array($value, $private_key)){
					$position = array_search( $value, $private_key);
					$char =$public_key[$position]; 
					$decrypted_text.=$char; 
				}else{
					$decrypted_text.=$value;
				}
			}else{
				$count++;
			}
		}

		return $decrypted_text;
	}
}



