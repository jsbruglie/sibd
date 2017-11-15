select region_overlaps_element(1,1,0.1,0.1,0.3,0.3);

-- +----------------------------------------------+
-- | region_overlaps_element(1,1,0.1,0.1,0.3,0.3) |
-- +----------------------------------------------+
-- |                                                     1 
-- +----------------------------------------------+
-- 1 row in set (0.00 sec)

select region_overlaps_element(1,1,0.1,0.1,0.6,0.6);

-- +----------------------------------------------+
-- | region_overlaps_element(1,1,0.1,0.1,0.6,0.6) |
-- +----------------------------------------------+
-- |                                                     1 
-- +----------------------------------------------+
-- 1 row in set (0.00 sec)

select region_overlaps_element(1,1,0.5,0.5,0.9,0.9);
-- +----------------------------------------------+
-- | region_overlaps_element(1,1,0.5,0.5,0.9,0.9) |
-- +----------------------------------------------+
-- |                                                     1 
-- +----------------------------------------------+
-- 1 row in set (0.00 sec)

select region_overlaps_element(1,1,0.45,0.45,0.55,0.55);
-- +--------------------------------------------------+
-- | region_overlaps_element(1,1,0.45,0.45,0.55,0.55) |
-- +--------------------------------------------------+
-- |                                                     0 
-- +--------------------------------------------------+
-- 1 row in set (0.00 sec)