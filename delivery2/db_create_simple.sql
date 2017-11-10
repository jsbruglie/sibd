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
			1.Create and update period (our addition).
			2.The doctor who prescribes is not the same that performs the exam
			3.A device cannot be associated to a patient in overlapping periods
			*/
DROP TRIGGER IF EXISTS check_period_insert;
DELIMITER $$
CREATE TRIGGER check_period_insert BEFORE INSERT ON period
FOR EACH row
BEGIN 
	IF DATEDIFF(new.end,new.start) < 0 then
		SIGNAL SQLSTATE '45000' set message_text = 'The period is not valid';
	END IF;	 

END;
$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_period_update;
DELIMITER $$
CREATE TRIGGER check_period_update BEFORE UPDATE ON period
FOR EACH row
BEGIN 
	IF DATEDIFF(new.end,new.start) < 0 then 
		SIGNAL SQLSTATE '45000' set message_text = 'The period is not valid';
	END IF;	 

END;
$$
DELIMITER ;