INSERT INTO `study` (`request_number`, `description`, `date`, `doctor_id`, `serial_number`, `manufacturer`) VALUES
    ('1', 'LDL cholesterol analysis', '2017-12-15', '1', 'a87S17UT6b', 'Medtronic')
-- ERROR 1644 (45000): That doctor cannot perform the exam.

UPDATE `study` SET `doctor_id` = '1' WHERE `study`.`request_number` = 1 AND `study`.`description` = 'LDL cholesterol analysis';
-- ERROR 1644 (45000): That doctor cannot perform the exam.