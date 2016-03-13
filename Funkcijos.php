<?php

require_once("duomenu_bazes_kontroleris.php");

function Ivesti_i_db($vardas, $pavarde, $slapt1, $slapt2, $data, $lytis, $telefonas, $pastas)
{

	$message ="Sveiki čia kalba funkcija :Ivesti_ib! (testavimas)";
	if (isset($vardas) and isset($pavarde) and isset($slapt1) and isset($slapt2) and isset($lytis) and isset($telefonas) and isset($pastas))

	{


			$db_objektas = new DB_Kontroleris();

			$Patikrinimo_Salyga = "SELECT * FROM registracijos_forma WHERE 	E_mail='$pastas'";
			$Tarpinis = $db_objektas->numRows($Patikrinimo_Salyga);

			if ($Tarpinis == 0) {
				$query = "INSERT INTO registracijos_forma (Vardas, Pavarde, Slaptazodis, Gimimo_diena, Lytis, Telefonas, E_mail) VALUES
		('" . $vardas . "','" . $pavarde . "','" . md5($slapt1) . "','" . $data . "','" . $lytis . "','" . $telefonas . "','" . $pastas . "')";
				$rezultatas = $db_objektas->insertQuery($query);
				if (!empty($rezultatas)) {
					$message = "Sėkmingai prisiregistravote!";
					unset($_POST);
				} else {
					$message = "Iškilo problemų, bandykite dar.";
					exit();
				}
			}
		else $message="Toks vartotojas jau yra!";

			}



	return $message;
}

function Patikrinti_Ar_vartotojas_Egzistuoja($pastas, $slaptazodis){
	$Ar_egzistuoja=false;


	$db_objektas = new DB_Kontroleris();

	$Patikrinimo_Salyga ="SELECT * FROM registracijos_forma WHERE 	E_mail='$pastas' AND Slaptazodis='".md5($slaptazodis)."'";
	$Tarpinis = $db_objektas->numRows($Patikrinimo_Salyga);

	if($Tarpinis==1)
	{
		$Ar_egzistuoja=true;
	}

	else
	{
		$Ar_egzistuoja = false;
	}


	return $Ar_egzistuoja;
}


function Grazinti_Prisijungusio_Asmens_Duomenis($ID){

	$db_objektas = new DB_Kontroleris();
	$uzklausa="SELECT * FROM registracijos_forma WHERE 	ID='$ID'";
	$Duomenys=$db_objektas->runQuery($uzklausa);

	return $Duomenys;
}


function Gauti_Vartotojo_ID($pastas, $slaptazodis)
{

	$db_objektas = new DB_Kontroleris();
	$uzklausa="SELECT ID FROM registracijos_forma WHERE 	E_mail='$pastas' AND Slaptazodis='".md5($slaptazodis)."'";
	$ID=$db_objektas->runQuery($uzklausa);

	return $ID;
}

function Gauti_Visus_Vartotojus() {

    $db_objektas = new DB_Kontroleris();
    $query = "SELECT * FROM registracijos_forma";
    $duomenys = $db_objektas->insertQuery($query);
        
    if (!$duomenys) {
    	$message  = 'Invalid query: ' . mysql_error() . "\n";
    	$message .= 'Whole query: ' . $query;
    	die($message);
    }
    return $duomenys;
}

function Gauti_Vartotojo_Duomenus_pagal_ID($ID)
{

	$db_objektas = new DB_Kontroleris();
	$query = "SELECT * FROM registracijos_forma WHERE ID='$ID'";
	$duomenys = $db_objektas->insertQuery($query);

	if (!$duomenys) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query;
		die($message);
	}
	return $duomenys;

}


function Sutikrinti_Ivedamus_Duomenis_Pries_registrujant($vardas, $pavarde, $slapt1, $slapt2, $data, $lytis, $telefonas, $pastas){

	$d = DateTime::createFromFormat('Y-m-d', $data);
	if($vardas == "" or $vardas == "Vardas" or !preg_match("/^[a-zA-Z ]*$/", $vardas) ) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if( $pavarde == "" or $pavarde == "Pavardė" or !preg_match("/^[a-zA-Z ]*$/", $pavarde)) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if(!filter_var($pastas, FILTER_VALIDATE_EMAIL) or($pastas == "" or $pastas == "E-mailas") ) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if($slapt1 == "" or $slapt1 == "Slaptažodis") $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if(strlen($slapt1) < 5) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if($slapt2 != $slapt1) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if($slapt1 != $slapt2) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if(strlen($slapt2) < 5) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if($slapt2 == "" or $slapt2 == "Slaptažodis") $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if(!is_numeric($telefonas) or strlen($telefonas) != 9) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	if($data == "" or $data == "Gimimo data") $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;


	if($d && $d->format('Y-m-d') == $data) $ar_teisingi_duoemnys=true;

	else 	$ar_teisingi_duoemnys=false;



	if (!isset($_POST["lytis"])) $ar_teisingi_duoemnys=false;
	else $ar_teisingi_duoemnys=true;

	return $ar_teisingi_duoemnys;
}
?>