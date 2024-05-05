DROP DATABASE MHS;
CREATE DATABASE IF NOT EXISTS MHS;

USE MHS;

CREATE TABLE Facility (
	Facility_ID INT AUTO_INCREMENT PRIMARY KEY,
    Street VARCHAR(100),
    City VARCHAR(100),
    State VARCHAR(100),
    Zip_Code INT(6),
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
    CASE WHEN NEW.Facility_Type = 'Office' THEN
        INSERT INTO Office (Facility_ID, Office_Count) VALUES (NEW.Facility_ID, NULL);
    ELSE
        INSERT INTO Outpatient_Surgery (Facility_ID, Procedure_Code, Procedure_Description, Room_Count) VALUES (NEW.Facility_ID, NULL, NULL, NULL);
    END CASE;
END;
//
DELIMITER ;

CREATE TABLE Employee (
	Employee_ID INT AUTO_INCREMENT PRIMARY KEY,
    SSN CHAR(9) NOT NULL,
    FName VARCHAR(100),
    MInit CHAR(1),
    LName VARCHAR(100),
    Street VARCHAR(100),
    City VARCHAR(100),
    State VARCHAR(100),
    Zip_Code INT(6),
    Salary DECIMAL(12,2),
    Hire_Date DATE,
    Job_Class ENUM('HCP','MD','Nurse','Admin'),
    Facility_ID INT
);

ALTER TABLE Employee
ADD CONSTRAINT fk_emp FOREIGN KEY (Facility_ID) REFERENCES Facility(Facility_ID);

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
    CASE WHEN NEW.Job_Class = 'HCP' THEN
        INSERT INTO Other_HCP (Employee_ID, Job_Title) VALUES (NEW.Employee_ID, NULL);
    WHEN NEW.Job_Class = 'MD' THEN
        INSERT INTO Doctor (Employee_ID, Specialty, Board_Certification_Date) VALUES (NEW.Employee_ID, NULL, NULL);
    WHEN NEW.Job_Class = 'Nurse' THEN
        INSERT INTO Nurse (Employee_ID, Certification) VALUES (NEW.Employee_ID, NULL);
    ELSE
        INSERT INTO Admin (Employee_ID, Job_Title) VALUES (NEW.Employee_ID, NULL);
    END CASE;
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
    Zip_Code INT(6),
    Primary_Physician_ID INT
);

ALTER TABLE Patient
ADD CONSTRAINT fk_patient FOREIGN KEY (Primary_Physician_ID) REFERENCES Doctor(Employee_ID);

CREATE TABLE Invoice (
	Invoice_ID INT AUTO_INCREMENT PRIMARY KEY,
    Invoice_Date DATE,
    Insurer_ID INT
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
    Zip_Code INT(6)
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

CREATE TABLE Treats (
	Employee_ID INT,
    Patient_ID INT
);

ALTER TABLE Treats
ADD CONSTRAINT pk_treats PRIMARY KEY (Employee_ID, Patient_ID);

ALTER TABLE Treats
ADD CONSTRAINT fk_treats1 FOREIGN KEY (Employee_ID) REFERENCES Doctor(Employee_ID);

ALTER TABLE Treats
ADD CONSTRAINT fk_treats2 FOREIGN KEY (Patient_ID) REFERENCES Patient(Patient_ID);
