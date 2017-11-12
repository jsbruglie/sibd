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
		AND DATEDIFF(end,new.start) > 0
		AND DATEDIFF(start,new.end) < 0) THEN
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
		AND DATEDIFF(end,new.start) > 0
		AND DATEDIFF(start,new.end) < 0) THEN
		SIGNAL SQLSTATE '45000' set message_text = 'There exists an overlapping period.';
	END IF;	 

END;
$$
DELIMITER ;