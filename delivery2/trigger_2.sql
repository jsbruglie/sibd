-- =================================================================================================
-- Author:      Daniel Sousa
--              Nuno Ferreira
--              João Borrego
--
-- Description: Triggers for wear insertion and update
--              A device cannot be associated with a patient in overlapping time periods
--              A patient may have several devices at the same time but a device is only associated
--              with a single patient during a given time period.
-- =================================================================================================

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