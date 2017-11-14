-- =================================================================================================
-- Author:      Daniel Sousa
--              Nuno Ferreira
--              João Borrego
--
-- Description: Returns the name of the patient with the highest number of readings of units of
--              "LDL cholesterol in mg/dL" over the value of 200, in the past 90 days
-- =================================================================================================

SELECT name FROM reading, wears, sensor, patient
WHERE datetime BETWEEN start AND end
    AND wears.snum = sensor.snum
    AND wears.start <= DATE(reading.datetime)
    AND wears.end >= DATE(reading.datetime)
    AND patient.number = wears.patient
    AND value > 200
    AND datediff(current_date(), cast(datetime AS date)) <= 90
    AND sensor.units = 'LDL cholesterol in mg/dL'
    GROUP BY name
    HAVING count(*) >= all(SELECT count(*) FROM reading, wears, sensor, patient
        WHERE datetime BETWEEN start AND end
            AND wears.snum = sensor.snum
            AND wears.start <= DATE(reading.datetime)
            AND wears.end >= DATE(reading.datetime)
            AND patient.number = wears.patient
            AND value > 200
            AND datediff(current_date(), cast(datetime AS date)) <= 90
            AND units = 'LDL cholesterol in mg/dL'
            GROUP BY name
    );