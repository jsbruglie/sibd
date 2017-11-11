/* 1st. patient name with the highest number of readings of units of "LDL cholesterol in mg/DL" in the past 90 days*/

SELECT name,units,datetime, max(value) FROM reading natural join wears, sensor, patient
WHERE datetime BETWEEN start AND end
AND wears.snum = sensor.snum
AND wears.patient = patient.number
AND DateDiff(current_date,cast(datetime as date)) <= 90
AND sensor.units = 'LDL cholesterol in mg/dL';
