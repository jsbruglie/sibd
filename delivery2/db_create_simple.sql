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
  date date NOT NULL,
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
  FOREIGN KEY (start,end) REFERENCES period (start, end),
  FOREIGN KEY (patient) REFERENCES patient (number),
  FOREIGN KEY (snum,manuf) REFERENCES device (serialnum, manufacturer)
);

ALTER TABLE doctor
  MODIFY doctor_id int NOT NULL AUTO_INCREMENT;
ALTER TABLE patient
  MODIFY number int NOT NULL AUTO_INCREMENT;
ALTER TABLE request
  MODIFY number int NOT NULL AUTO_INCREMENT;
ALTER TABLE series
  MODIFY series_id int NOT NULL AUTO_INCREMENT;

/* Triggers (need to be created for insertion and update): 
			1.Create and update period (our addition-since it makes the 3rd trigger easier). - done
			2.The doctor who prescribes is not the same that performs the exam - done
			3.A device cannot be associated to a patient in overlapping periods, the patient may have several devices at the same time
			but a device is only associanted to one patient during on period.
			*/
DROP TRIGGER IF EXISTS check_period_insert;
DELIMITER $$
CREATE TRIGGER check_period_insert BEFORE INSERT ON period
FOR EACH row
BEGIN 
	IF DATEDIFF(new.end,new.start) < 0 THEN
		SIGNAL SQLSTATE '45000' set message_text = 'The period is not valid.';
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
		SIGNAL SQLSTATE '45000' set message_text = 'The period is not valid.';
	END IF;	 

END;
$$
DELIMITER ;

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
		AND new.start < end
		AND new.end > start) THEN
		SIGNAL SQLSTATE '45000' set message_text = 'There exists an overlapping period.';
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
		AND new.start < end
		AND new.end > start) THEN
		SIGNAL SQLSTATE '45000' set message_text = 'There exists an overlapping period.';
	END IF;	 

END;
$$
DELIMITER ;

/*Function: Receives a series id and an index (for Region A), also receives x1,y1,x2,y2 (Region B) and checks
			if the any region of the element A overlaps with B, returns true.
*/
/*
DROP FUNCTION IF EXISTS region_overlaps_element;
DELIMITER $$
CREATE FUNCTION region_overlaps_element(series_id int,index int,x1 float,y1 float,x2 float,y2 float)
RETURNS BOOLEAN
BEGIN


END
$$
DELIMITER ;


/*
DELIMITER $$
CREATE FUNCTION overlap_rect( x1a FLOAT(4), y1a FLOAT(4) , x2a FLOAT(4) , y2a FLOAT(4) , x1b FLOAT(4) , x2b FLOAT(4) , y1b FLOAT(4) ,  y2b FLOAT(4) )
RETURNS BOOLEAN
BEGIN
IF ( (x2a < x1b) OR (x2b < x1a) ) THEN RETURN FALSE;
END IF;
IF( (y1a > y2b) OR (y1b > y2a) ) THEN RETURN FALSE;
END IF;
RETURN TRUE;
END $$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION overlap_serie(serie_id_a VARCHAR(255),serie_id_b VARCHAR(255))
RETURNS BOOLEAN
BEGIN
RETURN EXISTS (SELECT series_id FROM region AS r1, region AS r2
WHERE (r1.series_id = serie_id_a)
AND (r2.series_id = serie_id_b)
AND overlap_rect(r1.x1,r1.y1,r1.x2,r1.y2,r2.x1,r2.y1,r2.x2,r2.y2));
END $$
DELIMITER ;
*/