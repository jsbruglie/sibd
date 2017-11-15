INSERT INTO `period` (`start`, `end`) VALUES ('2017-11-03 00:00:00', '2017-11-02 00:00:00')
-- ERROR 1644 (45000): The period is not valid.

UPDATE `period` SET `end` = '2015-10-01 00:00:00' WHERE
    `period`.`start` = '2016-04-20 00:00:00' AND `period`.`end` = '2017-10-30 00:00:00'
-- ERROR 1644 (45000): The period is not valid. 