-- =================================================================================================
-- Author:      Daniel Sousa
--              Nuno Ferreira
--              João Borrego
--
-- Description: Creates the database
-- =================================================================================================

-- =================================================================================================
-- Create database
-- =================================================================================================

DROP TABLE IF EXISTS wears;
DROP TABLE IF EXISTS region;
DROP TABLE IF EXISTS reading;
DROP TABLE IF EXISTS sensor;
DROP TABLE IF EXISTS period;
DROP TABLE IF EXISTS element;
DROP TABLE IF EXISTS series;
DROP TABLE IF EXISTS study;
DROP TABLE IF EXISTS request;
DROP TABLE IF EXISTS device;
DROP TABLE IF EXISTS doctor;
DROP TABLE IF EXISTS patient;

CREATE TABLE patient (
    number int NOT NULL,
    name varchar(255) NOT NULL,
    birthday date NOT NULL,
    address varchar(255) NOT NULL,
    PRIMARY KEY (number)
);

ALTER TABLE `patient`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

CREATE TABLE doctor (
    doctor_id int NOT NULL,
    number int NOT NULL,
    PRIMARY KEY (doctor_id),
    FOREIGN KEY (number) REFERENCES patient (number)
);

CREATE TABLE device (
    serialnum varchar(255) NOT NULL,
    manufacturer varchar(255) NOT NULL,
    model varchar(255) NOT NULL,
    PRIMARY KEY (serialnum,manufacturer)
);

CREATE TABLE request (
    number int NOT NULL,
    patient_id int NOT NULL,
    doctor_id int NOT NULL,
    date_request date NOT NULL,
    PRIMARY KEY (number),
    FOREIGN KEY (patient_id,doctor_id) REFERENCES doctor (number,doctor_id)
);

CREATE TABLE study (
    request_number int NOT NULL,
    description varchar(255) NOT NULL,
    date date NOT NULL,
    doctor_id int NOT NULL,
    serial_number varchar(255) NOT NULL,
    manufacturer varchar(255) NOT NULL,
    PRIMARY KEY (request_number,description),
    FOREIGN KEY (request_number) REFERENCES request (number),
    FOREIGN KEY (doctor_id) REFERENCES doctor (doctor_id),
    FOREIGN KEY (serial_number,manufacturer) REFERENCES device (serialnum, manufacturer)
);

CREATE TABLE series (
    series_id int NOT NULL,
    name varchar(255) NOT NULL,
    base_url varchar(255) NOT NULL,
    request_number int NOT NULL,
    description varchar(255) NOT NULL,
    PRIMARY KEY (series_id),
    FOREIGN KEY (request_number,description) REFERENCES study (request_number, description)
);

CREATE TABLE element (
    series_id int NOT NULL,
    elem_index int NOT NULL,
    PRIMARY KEY (series_id,elem_index),
    FOREIGN KEY (series_id) REFERENCES series (series_id)
);

CREATE TABLE period (
    start datetime NOT NULL,
    end datetime NOT NULL,
    PRIMARY KEY (start,end)
);

CREATE TABLE sensor (
    snum varchar(255) NOT NULL,
    manuf varchar(255) NOT NULL,
    units varchar(255) NOT NULL,
    PRIMARY KEY (snum,manuf),
    FOREIGN KEY (snum,manuf) REFERENCES device (serialnum, manufacturer)
);

CREATE TABLE reading (
    snum varchar(255) NOT NULL,
    manuf varchar(255) NOT NULL,
    datetime datetime NOT NULL,
    value float NOT NULL,
    PRIMARY KEY (datetime),
    FOREIGN KEY (snum,manuf) REFERENCES sensor (snum, manuf)
);

CREATE TABLE region (
    series_id int NOT NULL,
    elem_index int NOT NULL,
    x1 float NOT NULL,
    y1 float NOT NULL,
    x2 float NOT NULL,
    y2 float NOT NULL,
    PRIMARY KEY (series_id,elem_index,x1,y1,x2,y2),
    FOREIGN KEY (series_id,elem_index) REFERENCES element (series_id, elem_index)
);

CREATE TABLE wears (
    start datetime NOT NULL,
    end datetime NOT NULL,
    snum varchar(255) NOT NULL,
    manuf varchar(255) NOT NULL,
    patient int NOT NULL,
    PRIMARY KEY (start,end,patient),
    FOREIGN KEY (start,end) REFERENCES period (start, end) ON UPDATE CASCADE,
    FOREIGN KEY (patient) REFERENCES patient (number),
    FOREIGN KEY (snum,manuf) REFERENCES device (serialnum, manufacturer)
);

-- =================================================================================================
-- Populate database
-- =================================================================================================

INSERT INTO `patient` (`number`, `name`, `birthday`, `address`) VALUES
(1, 'Miley Dudley', '1977-12-07', 'Hillcrest Avenue'),
(2, 'Alivia Mack', '1989-04-07', 'Depot Street'),
(3, 'Raymond Glass', '1996-09-25', 'Summit Avenue'),
(4, 'David King', '1972-03-02', '8th Street West'),
(5, 'Louise White', '1967-05-05', 'Ivy Lane');

INSERT INTO `doctor` (`doctor_id`, `number`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

INSERT INTO `device` (`serialnum`, `manufacturer`, `model`) VALUES
('med_1', 'Medtronic', 'model m1'),
('med_2', 'Medtronic', 'model m2'),
('med_3', 'Medtronic', 'model m3'),
('med_4', 'Medtronic', 'model m4'),
('med_5', 'Medtronic', 'model m5'),
('vap_1', 'Vapor Medical', 'model v1'),
('vap_2', 'Vapor Medical', 'model v2'),
('vap_3', 'Vapor Medical', 'model v3'),
('zoom_1', 'Zoom Medical', 'model z1');

INSERT INTO `request` (`number`, `patient_id`, `doctor_id`, `date_request`) VALUES
(1, 1, 1, '2016-01-01'),
(2, 2, 2, '2017-01-01'),
(3, 2, 2, '2016-01-01'),
(4, 3, 3, '2016-01-01');

INSERT INTO `study` (`request_number`, `description`, `date`, `doctor_id`, `serial_number`, `manufacturer`) VALUES
(2, 'Study 1-1', '2016-07-25', 2, 'vap_1', 'Vapor Medical'),
(2, 'Study 1-2', '2016-08-25', 2, 'vap_1', 'Vapor Medical'),
(2, 'Study 1-3', '2016-09-25', 2, 'vap_1', 'Vapor Medical');

INSERT INTO `series` (`series_id`, `name`, `base_url`, `request_number`, `description`) VALUES
(1, 'Data series 1-1', 'http:/localhost/sibd/delivery3/html/series/1', 2, 'Study 1-1'),
(2, 'Data series 1-2', 'http:/localhost/sibd/delivery3/html/series/2', 2, 'Study 1-2'),
(3, 'Data series 1-3', 'http:/localhost/sibd/delivery3/html/series/3', 2, 'Study 1-3');

INSERT INTO `element` (`series_id`, `elem_index`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 2),
(3, 3);

INSERT INTO `period` (`start`, `end`) VALUES
('2016-10-10 00:00:00', '2017-10-10 00:00:00'),
('2017-10-10 00:00:00', '2999-12-31 00:00:00'),
('2017-08-01 00:00:00', '2999-12-31 00:00:00'),
('2016-06-20 00:00:00', '2017-07-05 00:00:00'),
('2017-07-05 00:00:00', '2999-12-31 00:00:00');

INSERT INTO `wears` (`start`, `end`, `snum`, `manuf`, `patient`) VALUES
('2016-10-10 00:00:00', '2017-10-10 00:00:00', 'med_2', 'Medtronic', 1),
('2017-10-10 00:00:00', '2999-12-31 00:00:00', 'med_1', 'Medtronic', 1),
('2017-08-01 00:00:00', '2999-12-31 00:00:00', 'vap_1', 'Vapor Medical', 1),
('2016-06-20 00:00:00', '2017-07-05 00:00:00', 'vap_1', 'Vapor Medical', 2),
('2017-07-05 00:00:00', '2999-12-31 00:00:00', 'vap_2', 'Vapor Medical', 2);

INSERT INTO `region` (`series_id`, `elem_index`, `x1`, `y1`, `x2`, `y2`) VALUES
(1, 1, 0.1, 0.1, 0.2, 0.2),
(1, 2, 0.3, 0.3, 0.4, 0.4),
(2, 1, 0.5, 0.5, 0.6, 0.6),
(2, 2, 0.7, 0.7, 0.8, 0.8);

-- =================================================================================================
-- Add triggers
-- =================================================================================================

DROP TRIGGER IF EXISTS check_doctor_insert;
DELIMITER $$
CREATE TRIGGER check_doctor_insert BEFORE INSERT ON study
FOR EACH row
BEGIN 
    IF EXISTS (SELECT * 
        FROM request 
        WHERE number = new.request_number 
        AND doctor_id = new.doctor_id) THEN
        SIGNAL SQLSTATE '45000' set message_text = 'That doctor cannot perform the exam.';
    END IF;  

END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_doctor_update;
DELIMITER $$
CREATE TRIGGER check_doctor_update BEFORE UPDATE ON study
FOR EACH row
BEGIN 
    IF EXISTS (SELECT * 
        FROM request 
        WHERE number = new.request_number 
        AND doctor_id = new.doctor_id) THEN
        SIGNAL SQLSTATE '45000' set message_text = 'That doctor cannot perform the exam.';
    END IF;  

END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_overlap_insert;
DELIMITER $$
CREATE TRIGGER check_overlap_insert BEFORE INSERT ON wears
FOR EACH row
BEGIN
    IF EXISTS (SELECT *
        FROM wears
        WHERE snum = new.snum
        AND manuf = new.manuf
        AND DATEDIFF(end,new.start) > 0
        AND DATEDIFF(start,new.end) < 0) THEN
        SIGNAL SQLSTATE '45000' set message_text = 'Overlapping Periods.';
    END IF;  

END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_overlap_update;
DELIMITER $$
CREATE TRIGGER check_overlap_update BEFORE UPDATE ON wears
FOR EACH row
BEGIN
    IF EXISTS (SELECT *
        FROM wears
        WHERE snum = new.snum
        AND manuf = new.manuf
        AND DATEDIFF(end,new.start) > 0
        AND DATEDIFF(start,new.end) < 0) THEN
        SIGNAL SQLSTATE '45000' set message_text = 'Overlapping Periods.';
    END IF;  

END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_period_insert;
DELIMITER $$
CREATE TRIGGER check_period_insert BEFORE INSERT ON period
FOR EACH row
BEGIN 
    IF DATEDIFF(new.end,new.start) < 0 THEN
        SIGNAL SQLSTATE '45000' set message_text = 'The period is not valid. End switched with start.';
    END IF;  

END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_period_update;
DELIMITER $$
CREATE TRIGGER check_period_update BEFORE UPDATE ON period
FOR EACH row
BEGIN 
    IF DATEDIFF(new.end,new.start) < 0 THEN 
        SIGNAL SQLSTATE '45000' set message_text = 'The period is not valid. End switched with start.';
    END IF;  

END;
$$
DELIMITER ;

-- =================================================================================================
-- Add function
-- =================================================================================================

DROP FUNCTION IF EXISTS region_overlaps;
DELIMITER $$
CREATE FUNCTION region_overlaps(x1a float, x2a float, y1a float, y2a float, x1b float, x2b float, y1b float, y2b float)
RETURNS BOOLEAN
BEGIN

/* Reorder coordinates */
IF x1a > x2a THEN SET x1a = x2a+x1a; SET x2a = x1a-x2a; SET x1a = x1a-x2a; END IF;
IF x1b > x2b THEN SET x1b = x2b+x1b; SET x2b = x1b-x2b; SET x1b = x1b-x2b; END IF;
IF y1a > y2a THEN SET y1a = y2a+y1a; SET y2a = y1a-y2a; SET y1a = y1a-y2a; END IF;
IF y1b > y2b THEN SET y1b = y2b+y1b; SET y2b = y1b-y2b; SET y1b = y1b-y2b; END IF;

IF x1b > x2a OR y1b > y2a OR x1a > x2b OR y1a > y2b THEN
RETURN FALSE;
ELSE
RETURN TRUE;
END IF;
END;
$$
DELIMITER ;

DROP FUNCTION IF EXISTS region_overlaps_element;
DELIMITER $$
CREATE FUNCTION region_overlaps_element(series_id int, elem_index int, x1 float, y1 float, x2 float, y2 float)
RETURNS BOOLEAN
BEGIN
RETURN EXISTS (SELECT * FROM region AS ra
WHERE (ra.series_id = series_id)
AND (ra.elem_index = elem_index)
AND region_overlaps(ra.x1,ra.x2,ra.y1,ra.y2,x1,y2,y1,y2));
END;
$$
DELIMITER ; 
