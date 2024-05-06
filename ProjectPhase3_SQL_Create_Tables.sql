DROP DATABASE MHS;
CREATE DATABASE IF NOT EXISTS MHS;

USE MHS;

CREATE TABLE Facility (
	Facility_ID INT AUTO_INCREMENT PRIMARY KEY,
    Street VARCHAR(100),
    City VARCHAR(100),
    State VARCHAR(100),
    Zip_Code CHAR(5),
    Size DECIMAL(12,2),
    Facility_Type ENUM('Office', 'OPS')
);

CREATE TABLE Office (
	Facility_ID INT,
    Office_Count INT
);

ALTER TABLE Office
ADD CONSTRAINT pk_office PRIMARY KEY (Facility_ID);

ALTER TABLE Office
ADD CONSTRAINT fk_office FOREIGN KEY (Facility_ID) REFERENCES Facility(Facility_ID);

CREATE TABLE Outpatient_Surgery (
	Facility_ID INT,
    Procedure_Code INT,
    Procedure_Description VARCHAR(255),
    Room_Count INT
);

ALTER TABLE Outpatient_Surgery
ADD CONSTRAINT pk_ops PRIMARY KEY (Facility_ID);

ALTER TABLE Outpatient_Surgery
ADD CONSTRAINT fk_ops FOREIGN KEY (Facility_ID) REFERENCES Facility(Facility_ID);

DELIMITER //
CREATE TRIGGER insert_new_facility_subclass
AFTER INSERT ON Facility
FOR EACH ROW
BEGIN
    IF NEW.Facility_Type = 'Office' THEN
        INSERT INTO Office (Facility_ID, Office_Count)
        VALUES (NEW.Facility_ID, NULL);
    ELSEIF NEW.Facility_Type = 'OPS' THEN
        INSERT INTO Outpatient_Surgery (Facility_ID, Procedure_Code, Procedure_Description, Room_Count)
        VALUES (NEW.Facility_ID, NULL, NULL, NULL);
    END IF;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER delete_cascade_facility_subclass
BEFORE DELETE ON Facility
FOR EACH ROW
BEGIN
	IF (SELECT Facility_Type FROM Facility WHERE Facility_ID = OLD.Facility_ID)='Office' THEN
		DELETE FROM Office WHERE Facility_ID = OLD.Facility_ID;
	ELSEIF (SELECT Facility_Type FROM Facility WHERE Facility_ID = OLD.Facility_ID)='OPS' THEN
		DELETE FROM Outpatient_Surgery WHERE Facility_ID = OLD.Facility_ID;
    END IF;
END;
//
DELIMITER ;

/*
DELIMITER //
CREATE TRIGGER update_facility_subclass_change
AFTER UPDATE ON Facility
FOR EACH ROW
BEGIN
    CASE WHEN (OLD.Facility_Type = 'OPS' AND NEW.Facility_Type = 'Office') THEN
		DELETE FROM Outpatient_Surgery WHERE Outpatient_Surgery.Facility_ID = OLD.Facility_ID;
        INSERT INTO Office (Facility_ID, Office_Count) VALUES (NEW.Facility_ID, NULL);
	WHEN (OLD.Facility_Type = 'OPS' AND NEW.Facility_Type = 'Office') THEN
		DELETE FROM Office WHERE Office.Facility_ID = OLD.Facility_ID;
        INSERT INTO Outpatient_Surgery (Facility_ID, Procedure_Code, 
        Procedure_Description, Room_Count)
        VALUES (NEW.Facility_ID, NULL, NULL, NULL);
    END CASE;
END;
//
DELIMITER;
*/

CREATE TABLE Employee (
	Employee_ID INT AUTO_INCREMENT PRIMARY KEY,
    SSN CHAR(9) NOT NULL,
    FName VARCHAR(100),
    MInit CHAR(1),
    LName VARCHAR(100),
    Street VARCHAR(100),
    City VARCHAR(100),
    State VARCHAR(100),
    Zip_Code CHAR(5),
    Salary DECIMAL(12,2),
    Hire_Date DATE,
    Job_Class ENUM('HCP','MD','Nurse','Admin'),
    Facility_ID INT
);

ALTER TABLE Employee
ADD CONSTRAINT fk_emp FOREIGN KEY (Facility_ID) REFERENCES Facility(Facility_ID);

DELIMITER //
CREATE TRIGGER delete_set_null_facility_id_in_employee
BEFORE DELETE ON Facility
FOR EACH ROW
BEGIN
	UPDATE Employee SET Facility_ID = NULL WHERE Facility_ID = OLD.Facility_ID;
END;
//
DELIMITER ;

ALTER TABLE Employee
ADD CONSTRAINT chk_emp_salary CHECK (Salary > 0);

CREATE TABLE Other_HCP (
	Employee_ID INT,
    Job_Title VARCHAR(50)
);

ALTER TABLE Other_HCP
ADD CONSTRAINT pk_hcp PRIMARY KEY (Employee_ID);

ALTER TABLE Other_HCP
ADD CONSTRAINT fk_hcp FOREIGN KEY (Employee_ID) REFERENCES Employee(Employee_ID);

CREATE TABLE Doctor (
	Employee_ID INT,
    Specialty VARCHAR(50),
    Board_Certification_Date DATE
);

ALTER TABLE Doctor
ADD CONSTRAINT pk_doctor PRIMARY KEY (Employee_ID);

ALTER TABLE Doctor
ADD CONSTRAINT fk_doctor FOREIGN KEY (Employee_ID) REFERENCES Employee(Employee_ID);

CREATE TABLE Nurse (
	Employee_ID INT,
    Certification VARCHAR(50)
);

ALTER TABLE Nurse
ADD CONSTRAINT pk_nurse PRIMARY KEY (Employee_ID);

ALTER TABLE Nurse
ADD CONSTRAINT fk_nurse FOREIGN KEY (Employee_ID) REFERENCES Employee(Employee_ID);

CREATE TABLE Admin (
	Employee_ID INT,
    Job_Title VARCHAR(50)
);

ALTER TABLE Admin
ADD CONSTRAINT pk_admin PRIMARY KEY (Employee_ID);

ALTER TABLE Admin
ADD CONSTRAINT fk_admin FOREIGN KEY (Employee_ID) REFERENCES Employee(Employee_ID);

DELIMITER //
CREATE TRIGGER check_ssn_length
BEFORE INSERT ON Employee
FOR EACH ROW
BEGIN
    IF LENGTH(NEW.SSN) != 9 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'SSN must be 9 characters long';
    END IF;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER insert_new_employee_subclass
AFTER INSERT ON Employee
FOR EACH ROW
BEGIN
    IF NEW.Job_Class = 'HCP' THEN
        INSERT INTO Other_HCP (Employee_ID, Job_Title) 
        VALUES (NEW.Employee_ID, NULL);
    ELSEIF NEW.Job_Class = 'MD' THEN
        INSERT INTO Doctor (Employee_ID, Specialty, Board_Certification_Date) 
        VALUES (NEW.Employee_ID, NULL, NULL);
    ELSEIF NEW.Job_Class = 'Nurse' THEN
        INSERT INTO Nurse (Employee_ID, Certification) 
        VALUES (NEW.Employee_ID, NULL);
    ELSEIF NEW.Job_Class = 'Admin' THEN
        INSERT INTO Admin (Employee_ID, Job_Title) 
        VALUES (NEW.Employee_ID, NULL);
    END IF;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER delete_cascade_employee_subclass
BEFORE DELETE ON Employee
FOR EACH ROW
BEGIN
	IF (SELECT Job_Class FROM Employee WHERE Employee_ID = OLD.Employee_ID)='HCP' THEN
		DELETE FROM Other_HCP WHERE Employee_ID = OLD.Employee_ID;
	ELSEIF (SELECT Job_Class FROM Employee WHERE Employee_ID = OLD.Employee_ID)='MD' THEN
		DELETE FROM Doctor WHERE Employee_ID = OLD.Employee_ID;
	ELSEIF (SELECT Job_Class FROM Employee WHERE Employee_ID = OLD.Employee_ID)='Nurse' THEN
		DELETE FROM Nurse WHERE Employee_ID = OLD.Employee_ID;
	ELSEIF (SELECT Job_Class FROM Employee WHERE Employee_ID = OLD.Employee_ID)='Admin' THEN
		DELETE FROM Admin WHERE Employee_ID = OLD.Employee_ID;
    END IF;
END;
//
DELIMITER ;

CREATE TABLE Patient (
	Patient_ID INT AUTO_INCREMENT PRIMARY KEY,
    FName VARCHAR(100),
    MInit CHAR(1),
    LName VARCHAR(100),
    Street VARCHAR(100),
    City VARCHAR(100),
    State VARCHAR(100),
    Zip_Code CHAR(5),
    Primary_Physician_ID INT
);

ALTER TABLE Patient
ADD CONSTRAINT fk_patient FOREIGN KEY (Primary_Physician_ID) REFERENCES Doctor(Employee_ID);

DELIMITER //
CREATE TRIGGER delete_set_null_doctor_id_in_patient
BEFORE DELETE ON Doctor
FOR EACH ROW
BEGIN
	UPDATE Patient SET Primary_Physician_ID = NULL WHERE Primary_Physician_ID = OLD.Employee_ID;
END;
//
DELIMITER ;


CREATE TABLE Invoice (
	Invoice_ID INT AUTO_INCREMENT PRIMARY KEY,
    Invoice_Date DATE,
    Insurer_ID INT NOT NULL
);

CREATE TABLE Invoice_Detail (
	Patient_ID INT,
    Physician_ID INT,
    Facility_ID INT,
    Date_Time DATETIME,
    Description VARCHAR(255),
	Invoice_ID INT,
    Cost DECIMAL(8, 2)
);

ALTER TABLE Invoice_Detail
ADD CONSTRAINT pk_inv_detail PRIMARY KEY (Patient_ID, Physician_ID, Facility_ID, Date_Time);

ALTER TABLE Invoice_Detail
ADD CONSTRAINT fk_inv_detail1 FOREIGN KEY (Patient_ID) REFERENCES Patient(Patient_ID);

ALTER TABLE Invoice_Detail
ADD CONSTRAINT fk_inv_detail2 FOREIGN KEY (Physician_ID) REFERENCES Doctor(Employee_ID);

ALTER TABLE Invoice_Detail
ADD CONSTRAINT fk_inv_detail3 FOREIGN KEY (Facility_ID) REFERENCES Facility(Facility_ID);

ALTER TABLE Invoice_Detail
ADD CONSTRAINT fk_inv_detail4 FOREIGN KEY (Invoice_ID) REFERENCES Invoice(Invoice_ID);

CREATE TABLE Insurance_Company (
	Insurer_ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Street VARCHAR(100),
    City VARCHAR(100),
    State VARCHAR(100),
    Zip_Code CHAR(5)
);

ALTER TABLE Invoice
ADD CONSTRAINT fk_invoice FOREIGN KEY (Insurer_ID) REFERENCES Insurance_Company(Insurer_ID);

CREATE TABLE Insured_By (
	Patient_ID INT,
    Insurer_ID INT
);

ALTER TABLE Insured_By
ADD CONSTRAINT pk_insured_by PRIMARY KEY (Patient_ID, Insurer_ID);

ALTER TABLE Insured_By
ADD CONSTRAINT fk_insured_by1 FOREIGN KEY (Patient_ID) REFERENCES Patient(Patient_ID);

ALTER TABLE Insured_By
ADD CONSTRAINT fk_insured_by2 FOREIGN KEY (Insurer_ID) REFERENCES Insurance_Company(Insurer_ID);


DELIMITER //
CREATE TRIGGER insert_new_appointment
AFTER INSERT ON Invoice_Detail
FOR EACH ROW
BEGIN
    UPDATE Invoice_Detail SET Cost = 0 -- Initially set the cost to 0
    WHERE Invoice_Detail.Patient_ID = NEW.Patient_ID 
		AND Invoice_Detail.Physician_ID = NEW.Physician_ID
		AND Invoice_Detail.Facility_ID = NEW.Facility_ID
        AND Invoice_Detail.Date_Time = NEW.Date_Time;
END;
//
DELIMITER ;


DELIMITER //
CREATE TRIGGER update_inv_detail_cost_invoice_generator
AFTER UPDATE ON Invoice_Detail
FOR EACH ROW
BEGIN
    IF (COST IS NOT NULL AND COST > 0) THEN 
		-- Create Invoice once Cost is updated
		INSERT INTO Invoice (Invoice_Date, Insurer_ID) 
        VALUES (CAST(OLD.Date_Time AS DATE),
        (SELECT Insurer_ID FROM Insured_By WHERE Insured_By.Patient_ID=OLD.Patient_ID));
        -- Once the Invoice has been created, retrieve that Invoice_ID and update Invoice_Detail
        -- The composite primary key fields retain their old values
        UPDATE Invoice_Detail SET Invoice_ID = (SELECT Invoice_ID 
        FROM Invoice ORDER BY Invoice_ID DESC LIMIT 1) 
        WHERE Invoice_Detail.Patient_ID = OLD.Patient_ID 
			AND Invoice_Detail.Physician_ID = OLD.Physician_ID
			AND Invoice_Detail.Facility_ID = OLD.Facility_ID
            AND Invoice_Detail.Date_Time = OLD.Date_Time;
	END IF;
END;
//
DELIMITER ;

CREATE TABLE Treats (
	Employee_ID INT,
    Patient_ID INT
);

ALTER TABLE Treats
ADD CONSTRAINT pk_treats PRIMARY KEY (Employee_ID, Patient_ID);

ALTER TABLE Treats
ADD CONSTRAINT fk_treats1 FOREIGN KEY (Employee_ID) REFERENCES Doctor(Employee_ID) ON DELETE CASCADE;

ALTER TABLE Treats
ADD CONSTRAINT fk_treats2 FOREIGN KEY (Patient_ID) REFERENCES Patient(Patient_ID) ON DELETE CASCADE;

-- At a minimum, the Primary_Physician_ID will treat a Patient_ID
DELIMITER //
CREATE TRIGGER insert_new_treats_default_pair
AFTER INSERT ON Patient
FOR EACH ROW
BEGIN
    INSERT INTO Treats(Employee_ID, Patient_ID) VALUES (NEW.Primary_Physician_ID,NEW.Patient_ID);
END;
//
DELIMITER ;

-- If a Patient has scheduled an appointment with a Physician, then add that pair to the Treats table
DELIMITER //
CREATE TRIGGER insert_new_treats_pairs_from_inv_detail
AFTER INSERT ON Invoice_Detail
FOR EACH ROW
BEGIN
	IF (SELECT COUNT(*) FROM Treats WHERE Physician_ID = NEW.Physician_ID AND Patient_ID = NEW.Patient_ID)=0 THEN
		INSERT INTO Treats(Employee_ID, Patient_ID) VALUES (NEW.Physician_ID,NEW.Patient_ID);
	END IF;
END;
//
DELIMITER ;
