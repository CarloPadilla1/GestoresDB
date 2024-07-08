-- Insert
CREATE OR REPLACE FUNCTION insert_patient(
    p_name VARCHAR,
    p_lastname VARCHAR,
    p_age INT,
    p_birthdate DATE,
    p_gender VARCHAR,
    p_address VARCHAR,
    p_phone_number VARCHAR,
    p_email VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Patients (name, lastname, age, birthdate, gender, address, phone_number, email)
    VALUES (p_name, p_lastname, p_age, p_birthdate, p_gender, p_address, p_phone_number, p_email);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_patient(
    p_patient_id INT,
    p_name VARCHAR,
    p_lastname VARCHAR,
    p_age INT,
    p_birthdate DATE,
    p_gender VARCHAR,
    p_address VARCHAR,
    p_phone_number VARCHAR,
    p_email VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Patients
    SET name = p_name, lastname = p_lastname, age = p_age, birthdate = p_birthdate,
        gender = p_gender, address = p_address, phone_number = p_phone_number, email = p_email
    WHERE patient_id = p_patient_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_patient(p_patient_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Patients WHERE patient_id = p_patient_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_patient(p_patient_id INT) RETURNS TABLE(
    patient_id INT,
    name VARCHAR,
    lastname VARCHAR,
    age INT,
    birthdate DATE,
    gender VARCHAR,
    address VARCHAR,
    phone_number VARCHAR,
    email VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Patients WHERE patient_id = p_patient_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_appointment_state(p_state VARCHAR) RETURNS VOID AS $$
BEGIN
    INSERT INTO Appointment_state (state) VALUES (p_state);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_appointment_state(p_appointment_state_id INT, p_state VARCHAR) RETURNS VOID AS $$
BEGIN
    UPDATE Appointment_state
    SET state = p_state
    WHERE appointment_state_id = p_appointment_state_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_appointment_state(p_appointment_state_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Appointment_state WHERE appointment_state_id = p_appointment_state_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_appointment_state(p_appointment_state_id INT) RETURNS TABLE(
    appointment_state_id INT,
    state VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Appointment_state WHERE appointment_state_id = p_appointment_state_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_specialty(p_name VARCHAR, p_description VARCHAR) RETURNS VOID AS $$
BEGIN
    INSERT INTO Specialty (name, description) VALUES (p_name, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_specialty(p_specialty_id INT, p_name VARCHAR, p_description VARCHAR) RETURNS VOID AS $$
BEGIN
    UPDATE Specialty
    SET name = p_name, description = p_description
    WHERE specialty_id = p_specialty_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_specialty(p_specialty_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Specialty WHERE specialty_id = p_specialty_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_specialty(p_specialty_id INT) RETURNS TABLE(
    specialty_id INT,
    name VARCHAR,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Specialty WHERE specialty_id = p_specialty_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_medical_department(p_name VARCHAR, p_description VARCHAR) RETURNS VOID AS $$
BEGIN
    INSERT INTO Medical_department (name, description) VALUES (p_name, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_medical_department(p_medical_department_id INT, p_name VARCHAR, p_description VARCHAR) RETURNS VOID AS $$
BEGIN
    UPDATE Medical_department
    SET name = p_name, description = p_description
    WHERE medical_department_id = p_medical_department_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_medical_department(p_medical_department_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Medical_department WHERE medical_department_id = p_medical_department_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_medical_department(p_medical_department_id INT) RETURNS TABLE(
    medical_department_id INT,
    name VARCHAR,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Medical_department WHERE medical_department_id = p_medical_department_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_medical_staff(
    p_specialty_id INT,
    p_medical_department_id INT,
    p_name VARCHAR,
    p_lastname VARCHAR,
    p_phone_number VARCHAR,
    p_email VARCHAR,
    p_work_position VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Medical_staff (specialty_id, medical_department_id, name, lastname, phone_number, email, work_position)
    VALUES (p_specialty_id, p_medical_department_id, p_name, p_lastname, p_phone_number, p_email, p_work_position);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_medical_staff(
    p_medical_id INT,
    p_specialty_id INT,
    p_medical_department_id INT,
    p_name VARCHAR,
    p_lastname VARCHAR,
    p_phone_number VARCHAR,
    p_email VARCHAR,
    p_work_position VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Medical_staff
    SET specialty_id = p_specialty_id, medical_department_id = p_medical_department_id, name = p_name,
        lastname = p_lastname, phone_number = p_phone_number, email = p_email, work_position = p_work_position
    WHERE medical_id = p_medical_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_medical_staff(p_medical_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Medical_staff WHERE medical_id = p_medical_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_medical_staff(p_medical_id INT) RETURNS TABLE(
    medical_id INT,
    specialty_id INT,
    medical_department_id INT,
    name VARCHAR,
    lastname VARCHAR,
    phone_number VARCHAR,
    email VARCHAR,
    work_position VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Medical_staff WHERE medical_id = p_medical_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_medical_resources(
    p_medical_department_id INT,
    p_resource_type VARCHAR,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Medical_resources (medical_department_id, resource_type, description)
    VALUES (p_medical_department_id, p_resource_type, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_medical_resources(
    p_resources_id INT,
    p_medical_department_id INT,
    p_resource_type VARCHAR,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Medical_resources
    SET medical_department_id = p_medical_department_id, resource_type = p_resource_type, description = p_description
    WHERE resources_id = p_resources_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_medical_resources(p_resources_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Medical_resources WHERE resources_id = p_resources_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_medical_resources(p_resources_id INT) RETURNS TABLE(
    resources_id INT,
    medical_department_id INT,
    resource_type VARCHAR,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Medical_resources WHERE resources_id = p_resources_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_payment_type(p_name VARCHAR, p_description VARCHAR) RETURNS VOID AS $$
BEGIN
    INSERT INTO Payment_type (name, description) VALUES (p_name, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_payment_type(p_payment_type_id INT, p_name VARCHAR, p_description VARCHAR) RETURNS VOID AS $$
BEGIN
    UPDATE Payment_type
    SET name = p_name, description = p_description
    WHERE payment_type_id = p_payment_type_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_payment_type(p_payment_type_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Payment_type WHERE payment_type_id = p_payment_type_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_payment_type(p_payment_type_id INT) RETURNS TABLE(
    payment_type_id INT,
    name VARCHAR,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Payment_type WHERE payment_type_id = p_payment_type_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_family_background(
    p_patient_id INT,
    p_name VARCHAR,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO family_background (patient_id, name, description)
    VALUES (p_patient_id, p_name, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_family_background(
    p_background_id INT,
    p_patient_id INT,
    p_name VARCHAR,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE family_background
    SET patient_id = p_patient_id, name = p_name, description = p_description
    WHERE background_id = p_background_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_family_background(p_background_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM family_background WHERE background_id = p_background_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_family_background(p_background_id INT) RETURNS TABLE(
    background_id INT,
    patient_id INT,
    name VARCHAR,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM family_background WHERE background_id = p_background_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_medical_history(
    p_patient_id INT,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Medical_history (patient_id, description)
    VALUES (p_patient_id, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_medical_history(
    p_h_medical_id INT,
    p_patient_id INT,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Medical_history
    SET patient_id = p_patient_id, description = p_description
    WHERE h_medical_id = p_h_medical_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_medical_history(p_h_medical_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Medical_history WHERE h_medical_id = p_h_medical_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_medical_history(p_h_medical_id INT) RETURNS TABLE(
    h_medical_id INT,
    patient_id INT,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Medical_history WHERE h_medical_id = p_h_medical_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_appointment(
    p_patient_id INT,
    p_doctor_id INT,
    p_appointment_state_id INT,
    p_appointment_date DATE,
    p_appointment_time TIMESTAMP,
    p_reason_for_appointment TEXT,
    p_observations TEXT
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Appointments (patient_id, doctor_id, appointment_state_id, appointment_date, appointment_time, reason_for_appointment, observations)
    VALUES (p_patient_id, p_doctor_id, p_appointment_state_id, p_appointment_date, p_appointment_time, p_reason_for_appointment, p_observations);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_appointment(
    p_appointment_id INT,
    p_patient_id INT,
    p_doctor_id INT,
    p_appointment_state_id INT,
    p_appointment_date DATE,
    p_appointment_time TIMESTAMP,
    p_reason_for_appointment TEXT,
    p_observations TEXT
) RETURNS VOID AS $$
BEGIN
    UPDATE Appointments
    SET patient_id = p_patient_id, doctor_id = p_doctor_id, appointment_state_id = p_appointment_state_id,
        appointment_date = p_appointment_date, appointment_time = p_appointment_time, reason_for_appointment = p_reason_for_appointment,
        observations = p_observations
    WHERE appointment_id = p_appointment_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_appointment(p_appointment_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Appointments WHERE appointment_id = p_appointment_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_appointment(p_appointment_id INT) RETURNS TABLE(
    appointment_id INT,
    patient_id INT,
    doctor_id INT,
    appointment_state_id INT,
    appointment_date DATE,
    appointment_time TIMESTAMP,
    reason_for_appointment TEXT,
    observations TEXT
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Appointments WHERE appointment_id = p_appointment_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_bill(
    p_appointment_id INT,
    p_payment_type_id INT,
    p_bill_date DATE,
    p_description TEXT,
    p_total DECIMAL
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Bills (appointment_id, payment_type_id, bill_date, description, total)
    VALUES (p_appointment_id, p_payment_type_id, p_bill_date, p_description, p_total);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_bill(
    p_bill_id INT,
    p_appointment_id INT,
    p_payment_type_id INT,
    p_bill_date DATE,
    p_description TEXT,
    p_total DECIMAL
) RETURNS VOID AS $$
BEGIN
    UPDATE Bills
    SET appointment_id = p_appointment_id, payment_type_id = p_payment_type_id, bill_date = p_bill_date,
        description = p_description, total = p_total
    WHERE bill_id = p_bill_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_bill(p_bill_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Bills WHERE bill_id = p_bill_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_bill(p_bill_id INT) RETURNS TABLE(
    bill_id INT,
    appointment_id INT,
    payment_type_id INT,
    bill_date DATE,
    description TEXT,
    total DECIMAL
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Bills WHERE bill_id = p_bill_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_medical_consultation(
    p_appointment_id INT,
    p_consultation_date DATE,
    p_symptoms VARCHAR,
    p_diagnosis VARCHAR,
    p_treatment VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Medical_consultations (appointment_id, consultation_date, symptoms, diagnosis, treatment)
    VALUES (p_appointment_id, p_consultation_date, p_symptoms, p_diagnosis, p_treatment);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_medical_consultation(
    p_consultation_id INT,
    p_appointment_id INT,
    p_consultation_date DATE,
    p_symptoms VARCHAR,
    p_diagnosis VARCHAR,
    p_treatment VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Medical_consultations
    SET appointment_id = p_appointment_id, consultation_date = p_consultation_date,
        symptoms = p_symptoms, diagnosis = p_diagnosis, treatment = p_treatment
    WHERE consultation_id = p_consultation_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_medical_consultation(p_consultation_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Medical_consultations WHERE consultation_id = p_consultation_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_medical_consultation(p_consultation_id INT) RETURNS TABLE(
    consultation_id INT,
    appointment_id INT,
    consultation_date DATE,
    symptoms VARCHAR,
    diagnosis VARCHAR,
    treatment VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Medical_consultations WHERE consultation_id = p_consultation_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_vital_signs(
    p_consultation_id INT,
    p_appointment_id INT,
    p_blood_pressure VARCHAR,
    p_heart_rate VARCHAR,
    p_breathing_frequency VARCHAR,
    p_temperature VARCHAR,
    p_weight VARCHAR,
    p_height VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Vital_signs (consultation_id, appointment_id, blood_pressure, heart_rate, breathing_frequency, temperature, weight, height)
    VALUES (p_consultation_id, p_appointment_id, p_blood_pressure, p_heart_rate, p_breathing_frequency, p_temperature, p_weight, p_height);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_vital_signs(
    p_sign_id INT,
    p_consultation_id INT,
    p_appointment_id INT,
    p_blood_pressure VARCHAR,
    p_heart_rate VARCHAR,
    p_breathing_frequency VARCHAR,
    p_temperature VARCHAR,
    p_weight VARCHAR,
    p_height VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Vital_signs
    SET consultation_id = p_consultation_id, appointment_id = p_appointment_id, blood_pressure = p_blood_pressure,
        heart_rate = p_heart_rate, breathing_frequency = p_breathing_frequency, temperature = p_temperature,
        weight = p_weight, height = p_height
    WHERE sign_id = p_sign_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_vital_signs(p_sign_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Vital_signs WHERE sign_id = p_sign_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_vital_signs(p_sign_id INT) RETURNS TABLE(
    sign_id INT,
    consultation_id INT,
    appointment_id INT,
    blood_pressure VARCHAR,
    heart_rate VARCHAR,
    breathing_frequency VARCHAR,
    temperature VARCHAR,
    weight VARCHAR,
    height VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Vital_signs WHERE sign_id = p_sign_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_analysis_type(
    p_analysis_type VARCHAR,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Analysis_type (analysis_type, description)
    VALUES (p_analysis_type, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_analysis_type(
    p_analysis_id INT,
    p_analysis_type VARCHAR,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Analysis_type
    SET analysis_type = p_analysis_type, description = p_description
    WHERE analysis_id = p_analysis_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_analysis_type(p_analysis_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Analysis_type WHERE analysis_id = p_analysis_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_analysis_type(p_analysis_id INT) RETURNS TABLE(
    analysis_id INT,
    analysis_type VARCHAR,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Analysis_type WHERE analysis_id = p_analysis_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_analysis_result(
    p_consultation_id INT,
    p_appointment_id INT,
    p_result VARCHAR,
    p_observations VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Analysis_result (consultation_id, appointment_id, result, observations)
    VALUES (p_consultation_id, p_appointment_id, p_result, p_observations);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_analysis_result(
    p_analysis_result_id INT,
    p_consultation_id INT,
    p_appointment_id INT,
    p_result VARCHAR,
    p_observations VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Analysis_result
    SET consultation_id = p_consultation_id, appointment_id = p_appointment_id, result = p_result, observations = p_observations
    WHERE analysis_result_id = p_analysis_result_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_analysis_result(p_analysis_result_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Analysis_result WHERE analysis_result_id = p_analysis_result_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_analysis_result(p_analysis_result_id INT) RETURNS TABLE(
    analysis_result_id INT,
    consultation_id INT,
    appointment_id INT,
    result VARCHAR,
    observations VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Analysis_result WHERE analysis_result_id = p_analysis_result_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_treatment_type(
    p_name VARCHAR,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Treatment_type (name, description)
    VALUES (p_name, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_treatment_type(
    p_treatment_type_id INT,
    p_name VARCHAR,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Treatment_type
    SET name = p_name, description = p_description
    WHERE treatment_type_id = p_treatment_type_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_treatment_type(p_treatment_type_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Treatment_type WHERE treatment_type_id = p_treatment_type_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_treatment_type(p_treatment_type_id INT) RETURNS TABLE(
    treatment_type_id INT,
    name VARCHAR,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Treatment_type WHERE treatment_type_id = p_treatment_type_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_prescription(
    p_consultation_id INT,
    p_appointment_id INT,
    p_treatment_type_id INT,
    p_medicine VARCHAR,
    p_dose VARCHAR,
    p_instructions VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Prescription (consultation_id, appointment_id, treatment_type_id, medicine, dose, instructions)
    VALUES (p_consultation_id, p_appointment_id, p_treatment_type_id, p_medicine, p_dose, p_instructions);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_prescription(
    p_prescription_id INT,
    p_consultation_id INT,
    p_appointment_id INT,
    p_treatment_type_id INT,
    p_medicine VARCHAR,
    p_dose VARCHAR,
    p_instructions VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Prescription
    SET consultation_id = p_consultation_id, appointment_id = p_appointment_id, treatment_type_id = p_treatment_type_id,
        medicine = p_medicine, dose = p_dose, instructions = p_instructions
    WHERE prescription_id = p_prescription_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_prescription(p_prescription_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Prescription WHERE prescription_id = p_prescription_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_prescription(p_prescription_id INT) RETURNS TABLE(
    prescription_id INT,
    consultation_id INT,
    appointment_id INT,
    treatment_type_id INT,
    medicine VARCHAR,
    dose VARCHAR,
    instructions VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Prescription WHERE prescription_id = p_prescription_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_medical_prescription_analysis(
    p_prescription_id INT,
    p_analysis_result_id INT,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Medical_prescription_analysis (prescription_id, analysis_result_id, description)
    VALUES (p_prescription_id, p_analysis_result_id, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_medical_prescription_analysis(
    p_prescription_id INT,
    p_analysis_result_id INT,
    p_description VARCHAR
) RETURNS VOID AS $$
BEGIN
    UPDATE Medical_prescription_analysis
    SET description = p_description
    WHERE prescription_id = p_prescription_id AND analysis_result_id = p_analysis_result_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_medical_prescription_analysis(p_prescription_id INT, p_analysis_result_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Medical_prescription_analysis WHERE prescription_id = p_prescription_id AND analysis_result_id = p_analysis_result_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_medical_prescription_analysis(p_prescription_id INT, p_analysis_result_id INT) RETURNS TABLE(
    prescription_id INT,
    analysis_result_id INT,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Medical_prescription_analysis WHERE prescription_id = p_prescription_id AND analysis_result_id = p_analysis_result_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_insurance_company(p_name VARCHAR, p_description VARCHAR) RETURNS VOID AS $$
BEGIN
    INSERT INTO Insurance_company (name, description) VALUES (p_name, p_description);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_insurance_company(p_company_id INT, p_name VARCHAR, p_description VARCHAR) RETURNS VOID AS $$
BEGIN
    UPDATE Insurance_company
    SET name = p_name, description = p_description
    WHERE company_id = p_company_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_insurance_company(p_company_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Insurance_company WHERE company_id = p_company_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_insurance_company(p_company_id INT) RETURNS TABLE(
    company_id INT,
    name VARCHAR,
    description VARCHAR
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Insurance_company WHERE company_id = p_company_id;
END;
$$ LANGUAGE plpgsql;

-- Insert
CREATE OR REPLACE FUNCTION insert_insurance(
    p_patient_id INT,
    p_company_id INT,
    p_insurance_type VARCHAR,
    p_policy_number VARCHAR,
    p_validity_start DATE,
    p_validity_end DATE
) RETURNS VOID AS $$
BEGIN
    INSERT INTO Insurance (patient_id, company_id, insurance_type, policy_number, validity_start, validity_end)
    VALUES (p_patient_id, p_company_id, p_insurance_type, p_policy_number, p_validity_start, p_validity_end);
END;
$$ LANGUAGE plpgsql;

-- Update
CREATE OR REPLACE FUNCTION update_insurance(
    p_insurance_id INT,
    p_patient_id INT,
    p_company_id INT,
    p_insurance_type VARCHAR,
    p_policy_number VARCHAR,
    p_validity_start DATE,
    p_validity_end DATE
) RETURNS VOID AS $$
BEGIN
    UPDATE Insurance
    SET patient_id = p_patient_id, company_id = p_company_id, insurance_type = p_insurance_type,
        policy_number = p_policy_number, validity_start = p_validity_start, validity_end = p_validity_end
    WHERE insurance_id = p_insurance_id;
END;
$$ LANGUAGE plpgsql;

-- Delete
CREATE OR REPLACE FUNCTION delete_insurance(p_insurance_id INT) RETURNS VOID AS $$
BEGIN
    DELETE FROM Insurance WHERE insurance_id = p_insurance_id;
END;
$$ LANGUAGE plpgsql;

-- Select
CREATE OR REPLACE FUNCTION select_insurance(p_insurance_id INT) RETURNS TABLE(
    insurance_id INT,
    patient_id INT,
    company_id INT,
    insurance_type VARCHAR,
    policy_number VARCHAR,
    validity_start DATE,
    validity_end DATE
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM Insurance WHERE insurance_id = p_insurance_id;
END;
$$ LANGUAGE plpgsql;
