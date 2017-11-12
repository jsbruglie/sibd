/* 1st. patient name with the highest number of readings (higher thatn 200) of units of "LDL cholesterol in mg/DL" 
in the past 90 days*/

SELECT name,units, value FROM reading NATURAL JOIN wears, sensor, patient
WHERE datetime BETWEEN start AND end
AND wears.patient = patient.number
AND DateDiff(current_date,cast(datetime as date)) <= 90
AND sensor.units = 'LDL cholesterol in mg/dL'
AND value > 200
AND value>=all((SELECT value FROM reading 
	WHERE DateDiff(current_date,cast(datetime as date)) <= 90))
GROUP BY name;