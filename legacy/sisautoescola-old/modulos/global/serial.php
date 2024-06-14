<?php
function decode_serial($pChave, $pSerial) {
	$retorno = null;

	if (!isset($pChave) or !isset($pSerial) or $pChave == "" or $pSerial == "") {
		$retorno["status"] = 'erro';
		$retorno["msg"] = 'Chave ou Serial em branco.';
		return $retorno;
	}

	$arrChave = explode('.', base64_decode($pChave));
	$arrSerial = null;

	$arrSerial[] = $arrChave[0];

	$chIpServ = $arrChave[1];
    $chNomeServ = $arrChave[2];
    $chAdminServ = $arrChave[3];
    $chIpUser = $arrChave[4];

	if ($chIpServ == "S") {
        $ipServ = str_replace('.', '', $_SERVER['SERVER_ADDR']);
        $ipServ = str_replace(':', '', $ipServ);
        $arrSerial[] = $ipServ;
    } else {
        $arrSerial[] = "N";
    }
    if ($chNomeServ == "S") {
        $nomeServ = str_replace('.', '', $_SERVER['SERVER_NAME']);
        $nomeServ = str_replace('/', '', $nomeServ);
        $nomeServ = str_replace('\\', '', $nomeServ);
        $nomeServ = str_replace('|', '', $nomeServ);
        $nomeServ = str_replace('-', '', $nomeServ);
        $nomeServ = str_replace('+', '', $nomeServ);
        $nomeServ = str_replace('=', '', $nomeServ);
        $nomeServ = str_replace('?', '', $nomeServ);
        $nomeServ = str_replace(' ', '', $nomeServ);
        $arrSerial[] = $nomeServ;
    } else {
        $arrSerial[] = "N";
    }
    if ($chAdminServ == "S") {
		 $adminServ = str_replace('.', '', $_SERVER['SERVER_ADMIN']);
		 $adminServ = str_replace('/', '', $adminServ);
		 $adminServ = str_replace('\\', '', $adminServ);
		 $adminServ = str_replace('|', '', $adminServ);
		 $adminServ = str_replace('-', '', $adminServ);
		 $adminServ = str_replace('+', '', $adminServ);
		 $adminServ = str_replace('=', '', $adminServ);
		 $adminServ = str_replace('?', '', $adminServ);
		 $adminServ = str_replace(' ', '', $adminServ);
		 $arrSerial[] = $adminServ;
    } else {
		$arrSerial[] = "N";
    }

	$arrSerial[] = $arrChave[5];
	$arrSerial[] = $arrChave[6];

	$serial = md5(join('.', $arrSerial));

	if ($serial != $pSerial) {
		$retorno["status"] = 'erro';
		$retorno["msg"] = 'Serial inválido.';
		return $retorno;
	}

        $dataVenc = null;
        $dataVenc[] = substr($arrChave[6], 0, 4);
        $dataVenc[] = substr($arrChave[6], 4, 2);
        $dataVenc[] = substr($arrChave[6], 6, 2);
        $data1Time = mktime(0, 0, 0, $dataVenc[1], $dataVenc[2], $dataVenc[0]);
        $data2Time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

	if ($data1Time < $data2Time) {
		$retorno["status"] = 'aviso';
		$retorno["msg"] = 'Serial vencido. Solicite um novo.';
		return $retorno;
	}

	if ($arrChave[5] != "N") {
		$ipUser1 = substr($arrChave[5], 0, 3) * 1;
		$ipUser2 = substr($arrChave[5], 3, 3) * 1;
		$ipUser3 = substr($arrChave[5], 6, 3) * 1;
		$ipUser4 = substr($arrChave[5], 9, 3) * 1;

		$arrRemoteAddr = explode('.', $_SERVER['REMOTE_ADDR']);

		if ($ipUser1 != "CCC" and $ipUser1 != $arrRemoteAddr[0]) {
			$retorno["status"] = 'erro';
			$retorno["msg"] = 'Esta máquina não possui permissão para acessar este sistema.';
			return $retorno;
		}
		if ($ipUser2 != "CCC" and $ipUser2 != $arrRemoteAddr[1]) {
			$retorno["status"] = 'erro';
			$retorno["msg"] = 'Esta máquina não possui permissão para acessar este sistema.';
			return $retorno;
		}
		if ($ipUser3 != "CCC" and $ipUser3 != $arrRemoteAddr[2]) {
			$retorno["status"] = 'erro';
			$retorno["msg"] = 'Esta máquina não possui permissão para acessar este sistema.';
			return $retorno;
		}
		if ($ipUser4 != "CCC" and $ipUser4 != $arrRemoteAddr[3]) {
			$retorno["status"] = 'erro';
			$retorno["msg"] = 'Esta máquina não possui permissão para acessar este sistema.';
			return $retorno;
		}
	}

	$retorno["status"] = 'ok';
	return $retorno;
}
?>