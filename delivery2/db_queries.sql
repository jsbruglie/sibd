
/* 1st. patient name with the highest number of readings of units of "LDL cholesterol in mg/DL" in the past 90 days*/
SELECT name,units,datetime, value FROM reading natural join wears, sensor, patient
WHERE reading.snum = sensor.snum
AND reading.manuf = sensor.manuf
AND sensor.units = 'LDL cholesterol in mg/dL'
AND wears.patient = patient.number
AND DateDiff(current_time,cast(reading.datetime as date)) < =90;


SELECT name,units,datetime, max(value) FROM reading,sensor, wears, patient
WHERE DateDiff(current_date,cast(reading.datetime as date)) < 90
AND reading.snum = sensor.snum
AND wears.snum = sensor.snum
AND wears.patient = patient.number
AND sensor.units = 'LDL cholesterol in mg/dL';