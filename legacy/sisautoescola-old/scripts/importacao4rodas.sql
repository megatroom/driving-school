

ALTER TABLE `4rodasb`.`clientes - pessoal` CHANGE COLUMN `M�e` `Mae` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Identidade_Org�o` `Identidade_Orgao` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Nascimento_Munic�pio` `Nascimento_Municipio` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Endere�o` `Endereco` VARCHAR(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `N�mero` `Numero` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Observa��es` `Observacoes` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Conclu�do` `Concluido` TINYINT(1) NOT NULL,
 CHANGE COLUMN `Servi�o` `Servico` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;

