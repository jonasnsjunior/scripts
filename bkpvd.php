<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$dataHoje =  strftime('%A, %d de %B de %Y', strtotime('today'));
$dir    = "/var/spool/asterisk/monitorDONE/MP3";
$diretorio = scandir($dir);
$data = date("d-m-Y");

$bkp  = "IPBX - ERICOM TELECOMUNICACOES\n";
$bkp .= "TEL: (62) 4017-2455\n";
$bkp .= "GOIÂNIA, ".$dataHoje."\n";
$bkp .= "ROTINA DE BACKUP DOS ARQUIVOS DE AUDIO\n\n";

foreach($diretorio as $dadosDiretorio) {
	if ($dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/." && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/.." && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/bkp/" && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/log/" && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/bkp.php" ){
		
		$data_inicial = substr($dadosDiretorio, 0, 8);
		$data_final = date("Y-m-d");
		$diferenca = strtotime($data_final) - strtotime($data_inicial);
		$dias = floor($diferenca / (60 * 60 * 24));
		//var_dump ($data_inicial);
		if ($dias > 95 && $dias < 365 ){
			$bkp .=	"INICIO BACKUP DO DIRETORIO $dadosDiretorio\n\n";
			$bkp .= shell_exec("mkdir /var/spool/asterisk/monitorDONE/MP3/bkp/$data_inicial");
			$bkp .=	shell_exec("tar -czvf ".trim("/var/spool/asterisk/monitorDONE/MP3/".$dadosDiretorio).".tar.gz /var/spool/asterisk/monitorDONE/MP3/".$dadosDiretorio."\n\n");
			$bkp .=	"FIM BACKUP DO DIRETORIO $dadosDiretorio\n";
				if ($data_inicial = substr($dadosDiretorio, 0, 8)){
					$bkp .=	"MOVENDO ARQUIVO \"$dadosDiretorio.tar.gz\" PARA PASTA BKP\n";
					$bkp .=	shell_exec("mv /var/spool/asterisk/monitorDONE/MP3/*.tar.gz /var/spool/asterisk/monitorDONE/MP3/bkp/$data_inicial");
					$bkp .= "\n\n";
					echo $bkp;
			}
		}
	}
}

$dir = "/var/spool/asterisk/monitorDONE/MP3/";
$diretorio = scandir($dir);
$bkp .=	"INICIO REMOCAO DIRETORIOS\n\n";
foreach($diretorio as $dadosDiretorio) {
	if ($dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/." && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/.." && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/" && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/log" && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/bkp.php" ){
		
		$data_inicial = substr($dadosDiretorio, 0, 8);
		$data_final = date("Y-m-d");
		$diferenca = strtotime($data_final) - strtotime($data_inicial);
		$dias = floor($diferenca / (60 * 60 * 24));

		if ($dias > 95 && $dias < 365){
			$bkp .=	shell_exec("rm -rf /var/spool/asterisk/monitorDONE/MP3/".$dadosDiretorio."\n");
			$bkp .=	"DIRETORIO \"$dadosDiretorio\" REMOVIDO!\n";
			$dataRemocao[] = $dadosDiretorio;
			echo $bkp;
		}
	}
}
$bkp .=	"\nFIM REMOCAO DIRETORIOS\n\n";

$dir = 'bkp';
$diretorio = explode('.tar.gz', scandir($dir));
$bkp .=	"INICIO REMOCAO ARQUIVO DE BACKUP\n\n";
foreach($dataRemocao as $dadosDiretorio) {
	if ($dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/." && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/.." && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/bkp" && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/log" && $dadosDiretorio != "/var/spool/asterisk/monitorDONE/MP3/bkp.php" ){
		
		$data_inicial = substr($dadosDiretorio, 0, 8);
		$data_final = date("Y-m-d");
		$diferenca = strtotime($data_final) - strtotime($data_inicial);
		$dias = floor($diferenca / (60 * 60 * 24));
		
		if ($dias > 360 && $dias < 365){
			$bkp .=	shell_exec("rm -rf /var/spool/asterisk/monitorDONE/MP3/bkp/".$dadosDiretorio.".tar.gz\n");
			$bkp .=	"ARQUIVO \"$dadosDiretorio.tar.gz\" REMOVIDO!\n";
			echo $bkp;
		}
	}
}
$bkp .=	"\nFIM REMOCAO ARQUIVO DE BACKUP\n";


$name = 'Backup_'.$data.'.log'; //NOME ARQUIVO DE LOG
$file = fopen('/var/spool/asterisk/monitorDONE/MP3/log/'.$name, 'x+'); //DIRETORIO ARQUIVO DE LOG
fwrite($file, $bkp); //CRIANDO ARQUIVO DE LOG
fclose($file); //SALVANDO ARQUIVO DE LOG
?>
