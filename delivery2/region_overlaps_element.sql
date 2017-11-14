-- =================================================================================================
-- Author:      Daniel Sousa
--              Nuno Ferreira
--              João Borrego
--
-- Description: Determines whether two interest regions are overlapping
-- 
-- Parameters:  Series id and index identifying Region A
--              x1,y1,x2,y2         identifying Region B
-- =================================================================================================

DROP FUNCTION IF EXISTS region_overlaps;
DELIMITER $$
CREATE FUNCTION region_overlaps(x1a float, x2a float, y1a float, y2a float, x1b float, x2b float, y1b float, y2b float)
RETURNS BOOLEAN
BEGIN
    IF (((x1a BETWEEN x1b AND x2b) OR (x2a BETWEEN x1b AND x2b)) AND
        ((y1a BETWEEN y1b AND y2b) OR (y2a BETWEEN y1b AND y2b))) THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
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