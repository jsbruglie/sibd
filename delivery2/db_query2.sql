/*Query 2: Write a query to	retrieve the name(s) of	the	patient(s) who have been subject of studies	with
all devices	of manufacturer	“Medtronic”	in the past calendar year. 
-O paciente tem que usar todos os medtronics
*/

SELECT name, manufacturer FROM study, request, patient
WHERE YEAR(date) = YEAR(current_date - INTERVAL 1 YEAR)
AND study.manufacturer = 'Medtronic'
AND request.number = study.request_number
AND request.patient_id = patient.number
GROUP BY name
HAVING COUNT(distinct study.serial_number)  = (SELECT COUNT(device.manufacturer) FROM device
	WHERE device.manufacturer = 'Medtronic'
	);