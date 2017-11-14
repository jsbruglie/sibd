-- =================================================================================================
-- Author:      Daniel Sousa
--              Nuno Ferreira
--              João Borrego
--
-- Description: Triggers for doctor insertion and update
--              Ensures different doctors prescribe and perform an exam
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