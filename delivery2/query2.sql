-- =================================================================================================
-- Author:      Daniel Sousa
--              Nuno Ferreira
--              João Borrego
--
-- Description: Retrieves the names of the patients who have been subject of studies with ALL the
--              devices from the "Medtronic" manufacturer in the past calendar year 
-- =================================================================================================

SELECT name, manufacturer FROM study, request, patient
WHERE YEAR(date) = YEAR(current_date - INTERVAL 1 YEAR)
    AND study.manufacturer = 'Medtronic'
    AND request.number = study.request_number
    AND request.patient_id = patient.number
    GROUP BY name
    HAVING COUNT(distinct study.serial_number)  = (SELECT COUNT(device.manufacturer) FROM device
        WHERE device.manufacturer = 'Medtronic'
    );