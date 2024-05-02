USE MHS;
-- Management and Reporting:

-- 1.
CREATE VIEW Daily_Revenue_By_Facility_Subtotals AS
SELECT ID.Facility_ID AS Facility_ID, ID.Invoice_ID, ID.Cost AS Subtotals
FROM Invoice_Detail ID, Invoice I
WHERE ID.Invoice_ID = I.Invoice_ID AND I.Invoice_Date = 'choice_dt';

CREATE VIEW Daily_Revenue_By_Facility_Total AS
SELECT ID.Facility_ID AS Facility_ID, SUM(ID.Cost) AS Total_Revenue
FROM Invoice_Detail ID, Invoice I
WHERE ID.Invoice_ID = I.Invoice_ID AND I.Invoice_Date = 'choice_dt'
GROUP BY ID.Facility_ID;

-- 2.
CREATE VIEW Physician_Appointments_On_Date AS
SELECT Physician_ID, CAST(Date_Time AS DATE) AS Selected_Date, Facility_ID, Patient_ID, Date_Time
FROM Invoice_Detail
WHERE Physician_ID = 'choice_doc_id' AND CAST(Date_Time AS DATE) = 'choice_dt';

-- 3.
CREATE VIEW Facility_Appointments_In_Date_Range AS
SELECT Facility_ID, Date_Time, Physician_ID, Patient_ID, Description
FROM Invoice_Detail
WHERE Facility_ID = 'choice_fac_id' AND (CAST(Date_Time AS DATE) >= 'choice_begin_dt' AND CAST(Date_Time AS DATE) <= 'choice_end_dt');

-- 4.
CREATE VIEW Five_Best_Days_Revenue_MHS AS
SELECT T.Revenue_Date AS Revenue_Date, T.Total_Revenue AS Total_Revenue
FROM (SELECT I.Invoice_Date AS Revenue_Date, SUM(ID.Cost) AS Total_Revenue
FROM Invoice_Detail ID, Invoice I
WHERE ID.Invoice_ID = I.Invoice_ID
GROUP BY I.Invoice_Date) T
ORDER BY T.Total_Revenue DESC
LIMIT 5;

-- 5.
CREATE VIEW Ins_Company_Avg_Daily_Revenue_In_Date_Range AS
SELECT I.Insurer_ID AS Insurer_ID, AVG(ID.Cost) AS Average_Daily_Revenue
FROM Invoice_Detail ID, Invoice I
WHERE ID.Invoice_ID = I.Invoice_ID AND (I.Invoice_Date >= 'choice_begin_dt' AND I.Invoice_Date <= 'choice_end_dt')
GROUP BY I.Insurer_ID;
