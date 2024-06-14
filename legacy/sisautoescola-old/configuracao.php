<?php
define("CAMINHO_MODULOS", "modulos/");

include_once("modulos/global/global.php");

date_default_timezone_set('America/Sao_Paulo');

function __autoload($class_name) {
    $file_name = $class_name . '.php';
    if (file_exists($file_name)) {
        require_once $file_name;
    } else {
        $file_name = str_replace("_","/",$class_name) .'.php';
        if (file_exists($file_name)) {
            require_once $file_name;
            return true;
        }
        for ($i=0;$i<5;$i++) {
            $file_name = '../' . $file_name;
            if (file_exists($file_name)) {
                require_once $file_name;
                return true;
            }
        }
        $file_name = str_replace("_","\\",$class_name) .'.php';
        if (file_exists($file_name)) {
            require_once $file_name;
            return true;
        }
        for ($i=0;$i<5;$i++) {
            $file_name = '..\\' . $file_name;
            if (file_exists($file_name)) {
                require_once $file_name;
                return true;
            }
        }
        echo "Erro ao localizar a classe " . $class_name;
                
    }
}

/*
 * $pDate = dd/mm/yyyy
 */
function is_valid_date($pDate) {
    $dt=$pDate;
    $arr=explode("/",$dt);
    $dd=$arr[0]; 
    $mm=$arr[1]; 
    $yy=$arr[2]; 
    If(!checkdate($mm,$dd,$yy)){
        return false;
    }else {
        return true;
    }
}
function is_valid_time($pTime) {
    return true;
}

// Formato d/m/a
function addDayIntoDate($pDate,$pNumDays) {
	 $date = split("/",$pDate);	
     $thisyear = $date[2];
     $thismonth = $date[1];
     $thisday =  $date[0];
     $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday + $pNumDays, $thisyear );
     return strftime('%d/%m/%Y', $nextdate);
}

// Formato d/m/a
function subDayIntoDate($pDate,$pNumDays) {
     $date = split("/",$pDate);	
     $thisyear = $date[2];
     $thismonth = $date[1];
     $thisday =  $date[0];
     $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday - $pNumDays, $thisyear );
     return strftime('%d/%m/%Y', $nextdate);
}

function date_to_db($pDate) {
    if ($pDate == NULL) {
        return null;
    } else if ($pDate == "") {
        return "";
    }
    $dt=$pDate;
    $arr=explode("/",$dt); // splitting the array
    $dd=$arr[0]; // first element of the array is month
    $mm=$arr[1]; // second element is date
    $yy=$arr[2]; // third element is year
    return $yy ."-". $mm ."-". $dd;
}

function db_to_date($pDate) {
    if ($pDate == NULL) {
        return null;
    } else if ($pDate == "") {
        return "";
    }
    $arr=explode("-",$pDate);
    $yy=$arr[0];
    $mm=$arr[1];
    $dd=$arr[2];
    return $dd ."/". $mm ."/". $yy;
}

function db_to_week($pDate) {
    return diasemanaextenso(db_to_date($pDate));
}

function db_to_hour($pHour) {
    return substr($pHour, 0, 5);
}

// formatdo dd/mm/aaaa
function date_to_where($pField, $pDate) {
    $retorno = '';
    if (strpos($pDate, "/")) {
        $dt=$pDate;
        $arr=split("/",$dt);
        $count = 1;
        $retornoarr = null;
        foreach ($arr as $value) {
            if ($count == 1) {
                if (trim($value) != "") {
                    $retornoarr[] = "day(". $pField .") = ". trim($value);
                }
            } else if ($count == 2) {
                if (trim($value) != "") {
                    $retornoarr[] = "month(". $pField .") = ". trim($value);
                }
            } else if ($count == 3) {
                if (trim($value) != "") {
                    $retornoarr[] = "year(". $pField .") = ". trim($value);
                }
            }
            $count++;
        }
        $retorno = join(" and ", $retornoarr);
    } else {
        $retorno = "day(". $pField .") = ". $pDate;
    }
    return $retorno;
}

// AAAAMMDD para DD/MM/AAAA
function number_to_date($pDate) {
    $dd=substr($pDate, 6, 2); 
    $mm=substr($pDate, 4, 2); 
    $yy=substr($pDate, 0, 4); 
    return $dd ."/". $mm ."/". $yy;
}
// DD/MM/AAAA para AAAAMMDD
function date_to_number($pDate) {
    $arr=explode("/",$pDate); 
    $dd=substr("00".$arr[0], -2);
    $mm=substr("00".$arr[1], -2); 
    $yy=substr("0000".$arr[2], -4); 
    return  $yy . $mm . $dd;
}

function float_to_db($pFloat) {
    $valor = str_replace(".", "", $pFloat);
    $valor = str_replace(",", ".", $valor);
    return $valor;
}
function db_to_float($pFloat) {
    $valor = number_format($pFloat, 2, ',', '.');
    return $valor;
}

function getPost($pPost) {
    $resultGetPost[$pPost] = $_POST[$pPost];
    return $resultGetPost;
}

// Data modelo Brasileiro
function diasemana($pDate) {
    $date = explode("/",$pDate);
    $ano = $date[2];
    $mes = $date[1];
    $dia = $date[0];

    return date("w", mktime(0,0,0,$mes,$dia,$ano)) + 1;
}
// Data modelo Brasileiro
function diasemanaextenso($pDate) {
	$date = explode("/",$pDate);
        $ano = $date[2];
        $mes = $date[1];
        $dia = $date[0];

	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	switch($diasemana) {
		case"0": $diasemana = "Domingo";       break;
		case"1": $diasemana = "Segunda-Feira"; break;
		case"2": $diasemana = "Terça-Feira";   break;
		case"3": $diasemana = "Quarta-Feira";  break;
		case"4": $diasemana = "Quinta-Feira";  break;
		case"5": $diasemana = "Sexta-Feira";   break;
		case"6": $diasemana = "Sábado";        break;
	}

	return $diasemana;
}

function avisos_prioridade_to_str($pInt) {
    switch ($pInt) {
        case 0:
            return 'Alta';
            break;
        case 1:
            return 'Normal';
            break;
        case 2:
            return 'Baixa';
            break;
        default:
            return '';
            break;
    }
}
function avisos_status_to_str($pInt) {
    switch ($pInt) {
        case 'C':
            return 'Concluído';
            break;
        case 'A':
            return 'Ativo';
            break;
        default:
            return '';
            break;
    }
}
function agendamento_tipo_to_str($pTipo) {
    switch ($pTipo) {
        case 'A':
            return 'Aprovado';
            break;
        case 'R':
            return 'Reprovado';
            break;
        case 'C':
            return "Cancelado Aluno";
            break;
        case 'T':
            return "Retirado";
            break;
        case 'F':
            return "Falta";
            break;
        case 'N':
            return 'Não se aplica';
            break;
        default:
            return '';
            break;
    }
}
function examepratico_resultado_to_str($pTipo) {
    switch ($pTipo) {
        case 'A':
            return 'Aprovado';
            break;
        case 'R':
            return 'Reprovado';
            break;
        case 'N':
            return 'Não definido';
            break;
        case 'M':
            return 'Não Marcado';
            break;
        case 'T':
            return 'Retirado';
            break;
        case 'F':
            return 'Falta';
            break;
        default:
            return '';
            break;
    }
}
function aluno_sexo_to_str($pTipo) {
    switch ($pTipo) {
        case 'M':
            return 'Masculino';
            break;
        case 'F':
            return 'Feminino';
            break;
        default:
            return '';
            break;
    }
}
function validaCPF($cpf)
{	
    $cpf = str_pad(str_replace(array('-','.','/'), '', $cpf), 11, '0', STR_PAD_LEFT);
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' ||
            $cpf == '22222222222' || $cpf == '33333333333' ||
            $cpf == '44444444444' || $cpf == '55555555555' ||
            $cpf == '66666666666' || $cpf == '77777777777' ||
            $cpf == '88888888888' || $cpf == '99999999999') {
	return false;
    } else {
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }
}
function getIndexArraySelect($array, $key, $needle) {
    $resultado = -1;
    $iCont = 0;
    foreach ($array as $subArray) {
        if ($subArray[$key] == $needle) {
            $resultado = $iCont;
            break;
        }
        $iCont++;
    }
    return $resultado;
}
function formatar_numero($numero) {
    $resultado = "";
    if (is_numeric($numero)) {
        $resultado = number_format($numero, 2, ',', '.');
    }
    return $resultado;
}
?>