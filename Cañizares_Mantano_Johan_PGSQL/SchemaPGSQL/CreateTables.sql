CREATE TABLE Patients (
    patient_id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    lastname VARCHAR(255),
    age INT,
    birthdate DATE,
    gender VARCHAR(255),
    address VARCHAR(255),
    phone_number VARCHAR(50), 
    email VARCHAR(255)
);

CREATE TABLE Appointment_state (
    appointment_state_id SERIAL PRIMARY KEY,
    state VARCHAR(255)
);

CREATE TABLE Specialty (
    specialty_id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Medical_department (
    medical_department_id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

-- Crear Tablas Dependientes

CREATE TABLE Medical_staff (
    medical_id SERIAL PRIMARY KEY,
    specialty_id INT REFERENCES Specialty(specialty_id),
    medical_department_id INT REFERENCES Medical_department(medical_department_id),
    name VARCHAR(255),
    lastname VARCHAR(255),
    phone_number VARCHAR(50),
    email VARCHAR(255),
    work_position VARCHAR(255)
);

CREATE TABLE Medical_resources (
    resources_id SERIAL PRIMARY KEY,
    medical_department_id INT REFERENCES Medical_department(medical_department_id),
    resource_type VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Payment_type (
    payment_type_id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE family_background (
    background_id SERIAL PRIMARY KEY,
    patient_id INT REFERENCES Patients(patient_id),
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Medical_history (
    h_medical_id SERIAL PRIMARY KEY,
    patient_id INT REFERENCES Patients(patient_id),
    description VARCHAR(255)
);

CREATE TABLE Appointments (
    appointment_id SERIAL PRIMARY KEY,
    patient_id INT REFERENCES Patients(patient_id),
    doctor_id INT,
    appointment_state_id INT REFERENCES Appointment_state(appointment_state_id),
    appointment_date DATE,
    appointment_time TIMESTAMP, -- Ajustado a TIMESTAMP para PostgreSQL
    reason_for_appointment TEXT,
    observations TEXT
);

CREATE TABLE Bills (
    bill_id SERIAL PRIMARY KEY,
    appointment_id INT REFERENCES Appointments(appointment_id),
    payment_type_id INT REFERENCES Payment_type(payment_type_id),
    bill_date DATE,
    description TEXT,
    total DECIMAL(18, 2)
);

CREATE TABLE Medical_consultations (
    consultation_id SERIAL PRIMARY KEY,
    appointment_id INT REFERENCES Appointments(appointment_id),
    consultation_date DATE,
    symptoms VARCHAR(255),
    diagnosis VARCHAR(255),
    treatment VARCHAR(255)
);

CREATE TABLE Vital_signs (
    sign_id SERIAL PRIMARY KEY,
    consultation_id INT REFERENCES Medical_consultations(consultation_id),
    appointment_id INT REFERENCES Appointments(appointment_id),
    blood_pressure VARCHAR(255),
    heart_rate VARCHAR(255),
    breathing_frequency VARCHAR(255),
    temperature VARCHAR(255),
    weight VARCHAR(255),
    height VARCHAR(255)
);

CREATE TABLE Analysis_type (
    analysis_id SERIAL PRIMARY KEY,
    analysis_type VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Analysis_result (
    analysis_result_id SERIAL PRIMARY KEY,
    consultation_id INT REFERENCES Medical_consultations(consultation_id),
    appointment_id INT REFERENCES Appointments(appointment_id),
    result VARCHAR(255),
    observations VARCHAR(255)
);

CREATE TABLE Treatment_type (
    treatment_type_id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Prescription (
    prescription_id SERIAL PRIMARY KEY,
    consultation_id INT REFERENCES Medical_consultations(consultation_id),
    appointment_id INT REFERENCES Appointments(appointment_id),
    treatment_type_id INT REFERENCES Treatment_type(treatment_type_id),
    medicine VARCHAR(255),
    dose VARCHAR(255),
    instructions VARCHAR(255)
);

CREATE TABLE Medical_prescription_analysis (
    prescription_id INT REFERENCES Prescription(prescription_id),
    analysis_result_id INT REFERENCES Analysis_result(analysis_result_id),
    description VARCHAR(255),
    PRIMARY KEY (prescription_id, analysis_result_id)
);

CREATE TABLE Insurance_company (
    company_id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    description VARCHAR(255)
);

CREATE TABLE Insurance (
    insurance_id SERIAL PRIMARY KEY,
    patient_id INT REFERENCES Patients(patient_id),
    company_id INT REFERENCES Insurance_company(company_id),
    policy_number VARCHAR(50),
    expiration DATE
);
