USE MHS;
-- Patient Management:

-- Insert New Patient:
SELECT @new_patient_id := MAX(Patient_ID)+1 FROM Patient;
INSERT INTO Patient (
	Patient_ID, FName, MInit, LName, Street, City,
    State, Zip_Code, Primary_Physician_ID)
VALUES (@new_patient_id, '?', '?', '?', '?', '?', '?', '?', '?');

-- Update Patient records:
UPDATE Patient
SET FName = '', MInit = '', LName = '', Street = '', City = '',
    State = '', Zip_Code = '', Primary_Physician_ID = ''
WHERE Patient_ID = '';

-- View all Patient data:
CREATE VIEW Patient_View AS
SELECT * FROM Patient;

-- Create New Appointment:
INSERT INTO Invoice_Detail (
	Patient_ID, Physician_ID, Facility_ID, Date_Time, Description, Invoice_ID, Cost)
VALUES ('?', '?', '?', '?', '?', NULL, 0);

-- Update Invoice when Date_Time for Appointment/Invoice_Detail has passed, daily:
SELECT @rank := MAX(Invoice_ID) FROM Invoice;
CREATE TEMPORARY TABLE Invoice_Generation (
	New_ID INT,
    Inv_Date DATE,
    Ins_ID INT,
    Pat_ID INT,
    Doc_ID INT,
    Fac_ID INT,
    Inv_Date_Time DATETIME
);

INSERT INTO Invoice_Generation
SELECT @rank := @rank+1 AS New_ID,
	CAST(ID.Date_Time AS DATE) AS Inv_Date,
	IB.Insurer_ID AS Ins_ID,
    ID.Patient_ID AS Pat_ID,
    ID.Physician_ID AS Doc_ID,
    ID.Facility_ID AS Fac_ID,
    ID.Date_Time AS Inv_Date_Time
FROM Insured_By as IB, Invoice_Detail as ID
WHERE ID.Patient_ID = IB.Patient_ID AND CAST(ID.Date_Time AS DATE) >= current_date();

INSERT INTO Invoice (
	Invoice_ID, Invoice_Date, Insurer_ID)
SELECT New_ID, Inv_Date, Ins_ID FROM Invoice_Generation;

UPDATE Invoice_Detail as ID 
	INNER JOIN Invoice_Generation as IG 
	ON (ID.Patient_ID = IG.Pat_ID 
		AND ID.Physician_ID = IG.Doc_ID 
        AND ID.Facility_ID = IG.Fac_ID 
        AND ID.Date_Time = IG.Inv_Date_Time)
SET ID.Invoice_ID = IG.New_ID, ID.Cost = 1000
WHERE ID.Patient_ID = IG.Pat_ID 
	AND ID.Physician_ID = IG.Doc_ID 
	AND ID.Facility_ID = IG.Fac_ID 
	AND ID.Date_Time = IG.Inv_Date_Time;
DROP TEMPORARY TABLE Invoice_Generation;

-- Generate daily insurance company invoices with patient subtotals:
CREATE VIEW Daily_Ins_Comp_Invoices AS
SELECT I.Insurer_ID AS Insurer_ID,
	   T.Patient_ID AS Patient_ID,
       T.Total_Cost AS Total_Cost
FROM Invoice I, (SELECT Patient_ID, SUM(Cost) AS Total_Cost
				 FROM Invoice_Detail
                 WHERE Invoice_ID IS NOT NULL
                 GROUP BY Patient_ID) T
WHERE I.Invoice_ID = T.Invoice_ID;

