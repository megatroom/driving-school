

ALTER TABLE `4rodasb`.`clientes - pessoal` CHANGE COLUMN `Mãe` `Mae` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Identidade_Orgão` `Identidade_Orgao` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Nascimento_Município` `Nascimento_Municipio` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Endereço` `Endereco` VARCHAR(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Número` `Numero` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Observações` `Observacoes` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
 CHANGE COLUMN `Concluído` `Concluido` TINYINT(1) NOT NULL,
 CHANGE COLUMN `Serviço` `Servico` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL;

