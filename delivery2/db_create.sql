SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DROP TABLE IF EXISTS device;
CREATE TABLE device (
  serialnum varchar(255) COLLATE latin1_general_ci NOT NULL,
  manufacturer varchar(255) COLLATE latin1_general_ci NOT NULL,
  model varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS doctor;
CREATE TABLE doctor (
  doctor_id int(11) NOT NULL,
  number int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS element;
CREATE TABLE element (
  series_id int(11) NOT NULL,
  elem_index int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS patient;
CREATE TABLE patient (
  number int(11) NOT NULL,
  name varchar(255) COLLATE latin1_general_ci NOT NULL,
  birthday date NOT NULL,
  address varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS period;
CREATE TABLE period (
  start datetime NOT NULL,
  end datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS reading;
CREATE TABLE reading (
  snum varchar(255) COLLATE latin1_general_ci NOT NULL,
  manuf varchar(255) COLLATE latin1_general_ci NOT NULL,
  datetime datetime NOT NULL,
  value float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS region;
CREATE TABLE region (
  series_id int(11) NOT NULL,
  elem_index int(11) NOT NULL,
  x1 float NOT NULL,
  y1 float NOT NULL,
  x2 float NOT NULL,
  y2 float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS request;
CREATE TABLE request (
  number int(11) NOT NULL,
  patient_id int(11) NOT NULL,
  doctor_id int(11) NOT NULL,
  date date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS sensor;
CREATE TABLE sensor (
  snum varchar(255) COLLATE latin1_general_ci NOT NULL,
  manuf varchar(255) COLLATE latin1_general_ci NOT NULL,
  units varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS series;
CREATE TABLE series (
  series_id int(11) NOT NULL,
  name varchar(255) COLLATE latin1_general_ci NOT NULL,
  base_url varchar(255) COLLATE latin1_general_ci NOT NULL,
  request_number int(11) NOT NULL,
  description varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS study;
CREATE TABLE study (
  request_number int(11) NOT NULL,
  description varchar(255) COLLATE latin1_general_ci NOT NULL,
  date date NOT NULL,
  doctor_id int(11) NOT NULL,
  serial_number varchar(255) COLLATE latin1_general_ci NOT NULL,
  manufacturer varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

DROP TABLE IF EXISTS wears;
CREATE TABLE wears (
  start datetime NOT NULL,
  end datetime NOT NULL,
  snum varchar(255) COLLATE latin1_general_ci NOT NULL,
  manuf varchar(255) COLLATE latin1_general_ci NOT NULL,
  patient int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


ALTER TABLE device
  ADD PRIMARY KEY (serialnum,manufacturer);

ALTER TABLE doctor
  ADD PRIMARY KEY (doctor_id),
  ADD KEY number (number);

ALTER TABLE element
  ADD PRIMARY KEY (series_id,elem_index);

ALTER TABLE patient
  ADD PRIMARY KEY (number);

ALTER TABLE period
  ADD PRIMARY KEY (start,end);

ALTER TABLE reading
  ADD PRIMARY KEY (datetime),
  ADD KEY snum (snum,manuf);

ALTER TABLE region
  ADD PRIMARY KEY (series_id,elem_index,x1,y1,x2,y2);

ALTER TABLE request
  ADD PRIMARY KEY (number),
  ADD KEY patient_id (patient_id,doctor_id);

ALTER TABLE sensor
  ADD PRIMARY KEY (snum,manuf),
  ADD KEY snum (snum,manuf);

ALTER TABLE series
  ADD PRIMARY KEY (series_id),
  ADD KEY request_number (request_number,description);

ALTER TABLE study
  ADD PRIMARY KEY (request_number,description),
  ADD KEY request_number (request_number),
  ADD KEY doctor_id (doctor_id),
  ADD KEY serial_number (serial_number,manufacturer);

ALTER TABLE wears
  ADD PRIMARY KEY (start,end,patient),
  ADD KEY start (start),
  ADD KEY end (end),
  ADD KEY patient (patient),
  ADD KEY snum (snum,manuf);


ALTER TABLE doctor
  MODIFY doctor_id int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE patient
  MODIFY number int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE request
  MODIFY number int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE series
  MODIFY series_id int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE doctor
  ADD CONSTRAINT doctor_ibfk_1 FOREIGN KEY (number) REFERENCES patient (number);

ALTER TABLE element
  ADD CONSTRAINT element_ibfk_1 FOREIGN KEY (series_id) REFERENCES series (series_id);

ALTER TABLE reading
  ADD CONSTRAINT reading_ibfk_1 FOREIGN KEY (snum,manuf) REFERENCES sensor (snum, manuf);

ALTER TABLE region
  ADD CONSTRAINT region_ibfk_1 FOREIGN KEY (series_id,elem_index) REFERENCES element (series_id, elem_index);

ALTER TABLE request
  ADD CONSTRAINT request_ibfk_1 FOREIGN KEY (patient_id,doctor_id) REFERENCES doctor (number, doctor_id);

ALTER TABLE sensor
  ADD CONSTRAINT sensor_ibfk_1 FOREIGN KEY (snum,manuf) REFERENCES device (serialnum, manufacturer);

ALTER TABLE series
  ADD CONSTRAINT series_ibfk_1 FOREIGN KEY (request_number,description) REFERENCES study (request_number, description);

ALTER TABLE study
  ADD CONSTRAINT study_ibfk_1 FOREIGN KEY (request_number) REFERENCES request (number),
  ADD CONSTRAINT study_ibfk_2 FOREIGN KEY (doctor_id) REFERENCES doctor (doctor_id),
  ADD CONSTRAINT study_ibfk_3 FOREIGN KEY (serial_number,manufacturer) REFERENCES device (serialnum, manufacturer);

ALTER TABLE wears
  ADD CONSTRAINT wears_ibfk_1 FOREIGN KEY (start,end) REFERENCES period (start, end),
  ADD CONSTRAINT wears_ibfk_2 FOREIGN KEY (patient) REFERENCES patient (number),
  ADD CONSTRAINT wears_ibfk_3 FOREIGN KEY (snum,manuf) REFERENCES device (serialnum, manufacturer);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
