-- Crear Tablas Base

CREATE TABLE Patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    lastname VARCHAR(255),
    age INT,
    birthdate DATE,
    gender VARCHAR(255),
    address VARCHAR(255),
    phone_number BIGINT,
    email VARCHAR(255)
);

CREATE TABLE Appointment_state (
    appointment_state_id INT AUTO_INCREMENT PRIMARY KEY,
    state VARCHAR(255)
);

CREATE TABLE Specialty (
    specialty_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Medical_department (
    medical_department_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

-- Crear Tablas Dependientes

CREATE TABLE Medical_staff (
    medical_id INT AUTO_INCREMENT PRIMARY KEY,
    specialty_id INT,
    medical_department_id INT,
    name VARCHAR(255),
    lastname VARCHAR(255),
    phone_number BIGINT,
    email VARCHAR(255),
    work_position VARCHAR(255),
    FOREIGN KEY (specialty_id) REFERENCES Specialty(specialty_id),
    FOREIGN KEY (medical_department_id) REFERENCES Medical_department(medical_department_id)
);

CREATE TABLE Medical_resources (
    resources_id INT AUTO_INCREMENT PRIMARY KEY,
    medical_department_id INT,
    resource_type VARCHAR(255),
    description VARCHAR(255),
    FOREIGN KEY (medical_department_id) REFERENCES Medical_department(medical_department_id)
);

CREATE TABLE Payment_type (
    payment_type_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE family_background (
    background_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    name VARCHAR(255),
    description VARCHAR(255),
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id)
);

CREATE TABLE Medical_history (
    h_medical_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    description VARCHAR(255),
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id)
);

CREATE TABLE Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    doctor_id INT,
    appointment_state_id INT,
    appointment_date DATE,
    appointment_time TIMESTAMP,
    reason_for_appointment TEXT,
    observations TEXT,
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id),
    FOREIGN KEY (appointment_state_id) REFERENCES Appointment_state(appointment_state_id)
);

CREATE TABLE Bills (
    bill_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT,
    payment_type_id INT,
    bill_date DATE,
    description TEXT,
    total DECIMAL(10, 2),
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id),
    FOREIGN KEY (payment_type_id) REFERENCES Payment_type(payment_type_id)
);

CREATE TABLE Medical_consultations (
    consultation_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT,
    consultation_date DATE,
    symptoms VARCHAR(255),
    diagnosis VARCHAR(255),
    treatment VARCHAR(255),
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id)
);

CREATE TABLE Vital_signs (
    sign_id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT,
    appointment_id INT,
    blood_pressure VARCHAR(255),
    heart_rate VARCHAR(255),
    breathing_frequency VARCHAR(255),
    temperature VARCHAR(255),
    weight VARCHAR(255),
    height VARCHAR(255),
    FOREIGN KEY (consultation_id) REFERENCES Medical_consultations(consultation_id),
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id)
);

CREATE TABLE Analysis_type (
    analysis_id INT AUTO_INCREMENT PRIMARY KEY,
    analysis_type VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Analysis_result (
    analysis_result_id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT,
    appointment_id INT,
    result VARCHAR(255),
    observations VARCHAR(255),
    FOREIGN KEY (consultation_id) REFERENCES Medical_consultations(consultation_id),
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id)
);

CREATE TABLE Treatment_type (
    treatment_type_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Prescription (
    prescription_id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT,
    appointment_id INT,
    treatment_type_id INT,
    medicine VARCHAR(255),
    dose VARCHAR(255),
    instructions VARCHAR(255),
    FOREIGN KEY (consultation_id) REFERENCES Medical_consultations(consultation_id),
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id),
    FOREIGN KEY (treatment_type_id) REFERENCES Treatment_type(treatment_type_id)
);

CREATE TABLE Medical_prescription_analysis (
    prescription_id INT,
    analysis_result_id INT,
    description VARCHAR(255),
    PRIMARY KEY (prescription_id, analysis_result_id),
    FOREIGN KEY (prescription_id) REFERENCES Prescription(prescription_id),
    FOREIGN KEY (analysis_result_id) REFERENCES Analysis_result(analysis_result_id)
);

CREATE TABLE Insurance_company (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Insurance (
    insurance_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    company_id INT,
    policy_number BIGINT,
    expiration DATE,
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id),
    FOREIGN KEY (company_id) REFERENCES Insurance_company(company_id)
);
CREATE TABLE Auditoría (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(255),
    operation VARCHAR(10),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    old_values TEXT,
    new_values TEXT
);

