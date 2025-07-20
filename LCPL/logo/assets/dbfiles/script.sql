CREATE TABLE `your_table_name` (
  `ID No.` INT(4) NOT NULL,
  `School` VARCHAR(9) COLLATE utf8_general_ci DEFAULT NULL,
  `Date` DATE DEFAULT NULL,
  `Age` VARCHAR(10) COLLATE utf8_general_ci DEFAULT NULL,
  `Sex` VARCHAR(6) COLLATE utf8_general_ci DEFAULT NULL,
  `time` TIME DEFAULT NULL,
  `Elementry` VARCHAR(10) COLLATE utf8_general_ci DEFAULT NULL,
  `Highschool` VARCHAR(10) COLLATE utf8_general_ci DEFAULT NULL,
  `Shs` VARCHAR(10) COLLATE utf8_general_ci DEFAULT NULL,
  `College` VARCHAR(10) COLLATE utf8_general_ci DEFAULT NULL,
  `PostGrad` VARCHAR(10) COLLATE utf8_general_ci DEFAULT NULL,
  `Osy` VARCHAR(10) COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID No.`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;