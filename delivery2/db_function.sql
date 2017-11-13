/*Function: Receives a series id and an index (for Region A), also receives x1,y1,x2,y2 (Region B) and checks
			if the any region of the element A overlaps with B, returns true.
*/

DROP FUNCTION IF EXISTS region_overlaps;
DELIMITER $$
CREATE FUNCTION region_overlaps(x1a float, x2a float, y1a float, y2a float, x1b float, x2b float, y1b float, y2b float)
RETURNS BOOLEAN
BEGIN
/*ORDERS COORDINATES IF THEY ARE NOT ORDERED - I use the sum/subtraction because I am lazy and don't want to create variables*/
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