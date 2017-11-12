INSERT INTO `patient` (`number`, `name`, `birthday`, `address`) VALUES
(1, 'Miley Dudley', '1977-12-07', 'Hillcrest Avenue'),
(2, 'Alivia Mack', '1989-04-07', 'Depot Street'),
(3, 'Raymond Glass', '1996-09-25', 'Summit Avenue');

INSERT INTO `doctor` (`doctor_id`, `number`) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO `device` (`serialnum`, `manufacturer`, `model`) VALUES
('a87S17UT6b', 'Medtronic', 'device 1'),
('EuIeoloUxG', 'Vapor Medical', 'RGB camera'),
('svKAm324h3', 'Medtronic', 'device 1'),
('wZVhG2FFGh', 'Zoom Medical', 'dev 1');

INSERT INTO `request` (`number`, `patient_id`, `doctor_id`, `date_request`) VALUES
(1, 1, 1, '2016-08-06'),
(2,3,3,'2017-07-04'),
(3,1,1, '2016-08-07'),
(4,3,3, '2016-08-09');

INSERT INTO `study` (`request_number`, `description`, `date`, `doctor_id`, `serial_number`, `manufacturer`) VALUES
(1, 'LDL cholesterol analysis', '2016-08-30', 2, 'a87S17UT6b', 'Medtronic'),
(3, 'LDL cholesterol analysis', '2016-08-19', 2, 'svKAm324h3', 'Medtronic'),
(1, 'Posture Analysis', '2016-09-13', 3, 'EuIeoloUxG', 'Vapor Medical'),
(2, 'LDL cholesterol analysis', '2016-08-30', 2, 'a87S17UT6b', 'Medtronic'),
(4, 'LDL cholesterol analysis', '2016-08-19', 2, 'svKAm324h3', 'Medtronic');

INSERT INTO `series` (`series_id`, `name`, `base_url`, `request_number`, `description`) VALUES
(1, 'Posture analysis image collection', 'http://data_db.php?series_id=1', 1, 'Posture Analysis');

INSERT INTO `element` (`series_id`, `elem_index`) VALUES
(1, 1),
(1, 2),
(1, 3);

INSERT INTO `period` (`start`, `end`) VALUES
('2016-04-20 00:00:00', '2017-10-30 00:00:00'),
('2017-11-01 00:00:00', '2017-11-11 00:00:00');

INSERT INTO `sensor` (`snum`, `manuf`, `units`) VALUES
('a87S17UT6b', 'Medtronic', 'LDL cholesterol in mg/dL'),
('EuIeoloUxG', 'Vapor Medical', 'R8G8B8'),
('svKAm324h3', 'Medtronic', 'LDL cholesterol in mg/dL'),
('wZVhG2FFGh', 'Zoom Medical', 'mV');

INSERT INTO `reading` (`snum`, `manuf`, `datetime`,`value`) VALUES
('a87S17UT6b', 'Medtronic','2017-02-09 00:00:00', '201'),
('a87S17UT6b', 'Medtronic','2016-05-01 00:00:00', '200'),
('a87S17UT6b', 'Medtronic','2017-10-29 00:00:00', '230'),
('a87S17UT6b', 'Medtronic','2017-11-01 00:00:00', '210'),
('a87S17UT6b', 'Medtronic','2017-11-05 00:01:00', '210');

INSERT INTO `region` (`series_id`, `elem_index`, `x1`, `y1`, `x2`, `y2`) VALUES
(1, 1, 0.2, 0.2, 0.4, 0.4),
(1, 1, 0.6, 0.6, 0.8, 0.8);

INSERT INTO `wears` (`start`, `end`, `snum`, `manuf`, `patient`) VALUES
('2016-04-20 00:00:00', '2017-10-30 00:00:00', 'a87S17UT6b', 'Medtronic', 1),
('2017-11-01 00:00:00', '2017-11-11 00:00:00', 'a87S17UT6b', 'Medtronic', 3);