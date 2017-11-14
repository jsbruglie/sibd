-- =================================================================================================
-- Author:      Daniel Sousa
--              Nuno Ferreira
--              João Borrego
--
-- Description: Triggers for period insertion and update
--              Ensures a time period specifies an end datetime later than the start
-- =================================================================================================

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