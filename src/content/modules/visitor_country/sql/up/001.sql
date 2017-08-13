CREATE TABLE `{prefix}visitor_countries`
( `name` VARCHAR(100) NOT NULL , 
`value` INT NOT NULL DEFAULT '0' , 
PRIMARY KEY (`name`)) ENGINE = InnoDB;