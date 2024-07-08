
-- Crear Paciente
CREATE PROCEDURE CreatePatient(
    IN p_patient_id INT,
    IN p_name VARCHAR(255),
    IN p_lastname VARCHAR(255),
    IN p_age INT,
    IN p_birthdate DATE,
    IN p_gender VARCHAR(255),
    IN p_address VARCHAR(255),
    IN p_phone_number BIGINT,
    IN p_email VARCHAR(255)
)
BEGIN
    INSERT INTO Patients (patient_id, name, lastname, age, birthdate, gender, address, phone_number, email)
    VALUES (p_patient_id, p_name, p_lastname, p_age, p_birthdate, p_gender, p_address, p_phone_number, p_email);
END;

-- Actualizar Paciente
CREATE PROCEDURE UpdatePatient(
    IN p_patient_id INT,
    IN p_name VARCHAR(255),
    IN p_lastname VARCHAR(255),
    IN p_age INT,
    IN p_birthdate DATE,
    IN p_gender VARCHAR(255),
    IN p_address VARCHAR(255),
    IN p_phone_number BIGINT,
    IN p_email VARCHAR(255)
)
BEGIN
    UPDATE Patients
    SET name = p_name,
        lastname = p_lastname,
        age = p_age,
        birthdate = p_birthdate,
        gender = p_gender,
        address = p_address,
        phone_number = p_phone_number,
        email = p_email
    WHERE patient_id = p_patient_id;
END;

-- Eliminar Paciente
CREATE PROCEDURE DeletePatient(
    IN p_patient_id INT
)
BEGIN
    DELETE FROM Patients
    WHERE patient_id = p_patient_id;
END;

-- Crear Personal Médico
CREATE PROCEDURE CreateMedicalStaff(
    IN p_specialty_id INT,
    IN p_medical_department_id INT,
    IN p_name VARCHAR(255),
    IN p_lastname VARCHAR(255),
    IN p_phone_number BIGINT,
    IN p_email VARCHAR(255),
    IN p_work_position VARCHAR(255)
)
BEGIN
    INSERT INTO Medical_staff (specialty_id, medical_department_id, name, lastname, phone_number, email, work_position)
    VALUES (p_specialty_id, p_medical_department_id, p_name, p_lastname, p_phone_number, p_email, p_work_position);
END;

-- Leer Personal Médico
CREATE PROCEDURE GetMedicalStaff()
BEGIN
    SELECT * FROM Medical_staff;
END;

-- Actualizar Personal Médico
CREATE PROCEDURE UpdateMedicalStaff(
    IN p_medical_id INT,
    IN p_specialty_id INT,
    IN p_medical_department_id INT,
    IN p_name VARCHAR(255),
    IN p_lastname VARCHAR(255),
    IN p_phone_number BIGINT,
    IN p_email VARCHAR(255),
    IN p_work_position VARCHAR(255)
)
BEGIN
    UPDATE Medical_staff
    SET specialty_id = p_specialty_id,
        medical_department_id = p_medical_department_id,
        name = p_name,
        lastname = p_lastname,
        phone_number = p_phone_number,
        email = p_email,
        work_position = p_work_position
    WHERE medical_id = p_medical_id;
END;

-- Eliminar Personal Médico
CREATE PROCEDURE DeleteMedicalStaff(
    IN p_medical_id INT
)
BEGIN
    DELETE FROM Medical_staff
    WHERE medical_id = p_medical_id;
END;

-- Crear Cita
CREATE PROCEDURE CreateAppointment(
    IN p_patient_id INT,
    IN p_doctor_id INT,
    IN p_appointment_state_id INT,
    IN p_appointment_date DATE,
    IN p_appointment_time TIMESTAMP,
    IN p_reason_for_appointment TEXT,
    IN p_observations TEXT
)
BEGIN
    INSERT INTO Appointments (patient_id, doctor_id, appointment_state_id, appointment_date, appointment_time, reason_for_appointment, observations)
    VALUES (p_patient_id, p_doctor_id, p_appointment_state_id, p_appointment_date, p_appointment_time, p_reason_for_appointment, p_observations);
END;

-- Leer Citas
CREATE PROCEDURE GetAppointments()
BEGIN
    SELECT * FROM Appointments;
END;

-- Actualizar Cita
CREATE PROCEDURE UpdateAppointment(
    IN p_appointment_id INT,
    IN p_patient_id INT,
    IN p_doctor_id INT,
    IN p_appointment_state_id INT,
    IN p_appointment_date DATE,
    IN p_appointment_time TIMESTAMP,
    IN p_reason_for_appointment TEXT,
    IN p_observations TEXT
)
BEGIN
    UPDATE Appointments
    SET patient_id = p_patient_id,
        doctor_id = p_doctor_id,
        appointment_state_id = p_appointment_state_id,
        appointment_date = p_appointment_date,
        appointment_time = p_appointment_time,
        reason_for_appointment = p_reason_for_appointment,
        observations = p_observations
    WHERE appointment_id = p_appointment_id;
END;

-- Eliminar Cita
CREATE PROCEDURE DeleteAppointment(
    IN p_appointment_id INT
)
BEGIN
    DELETE FROM Appointments
    WHERE appointment_id = p_appointment_id;
END;

-- Crear Estado de Cita
CREATE PROCEDURE CreateAppointmentState(
    IN p_state VARCHAR(255)
)
BEGIN
    INSERT INTO Appointment_state (state)
    VALUES (p_state);
END;

-- Leer Estados de Cita
CREATE PROCEDURE GetAppointmentStates()
BEGIN
    SELECT * FROM Appointment_state;
END;

-- Actualizar Estado de Cita
CREATE PROCEDURE UpdateAppointmentState(
    IN p_appointment_state_id INT,
    IN p_state VARCHAR(255)
)
BEGIN
    UPDATE Appointment_state
    SET state = p_state
    WHERE appointment_state_id = p_appointment_state_id;
END;

-- Eliminar Estado de Cita
CREATE PROCEDURE DeleteAppointmentState(
    IN p_appointment_state_id INT
)
BEGIN
    DELETE FROM Appointment_state
    WHERE appointment_state_id = p_appointment_state_id;
END;

-- Crear Especialidad
CREATE PROCEDURE CreateSpecialty(
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Specialty (name, description)
    VALUES (p_name, p_description);
END;

-- Leer Especialidades
CREATE PROCEDURE GetSpecialties()
BEGIN
    SELECT * FROM Specialty;
END;

-- Actualizar Especialidad
CREATE PROCEDURE UpdateSpecialty(
    IN p_specialty_id INT,
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Specialty
    SET name = p_name,
        description = p_description
    WHERE specialty_id = p_specialty_id;
END;

-- Eliminar Especialidad
CREATE PROCEDURE DeleteSpecialty(
    IN p_specialty_id INT
)
BEGIN
    DELETE FROM Specialty
    WHERE specialty_id = p_specialty_id;
END;

-- Crear Departamento Médico
CREATE PROCEDURE CreateMedicalDepartment(
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Medical_department (name, description)
    VALUES (p_name, p_description);
END;

-- Leer Departamentos Médicos
CREATE PROCEDURE GetMedicalDepartments()
BEGIN
    SELECT * FROM Medical_department;
END;

-- Actualizar Departamento Médico
CREATE PROCEDURE UpdateMedicalDepartment(
    IN p_medical_department_id INT,
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Medical_department
    SET name = p_name,
        description = p_description
    WHERE medical_department_id = p_medical_department_id;
END;

-- Eliminar Departamento Médico
CREATE PROCEDURE DeleteMedicalDepartment(
    IN p_medical_department_id INT
)
BEGIN
    DELETE FROM Medical_department
    WHERE medical_department_id = p_medical_department_id;
END;

-- Crear Recurso Médico
CREATE PROCEDURE CreateMedicalResource(
    IN p_medical_department_id INT,
    IN p_resource_type VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Medical_resources (medical_department_id, resource_type, description)
    VALUES (p_medical_department_id, p_resource_type, p_description);
END;

-- Leer Recursos Médicos
CREATE PROCEDURE GetMedicalResources()
BEGIN
    SELECT * FROM Medical_resources;
END;

-- Actualizar Recurso Médico
CREATE PROCEDURE UpdateMedicalResource(
    IN p_resources_id INT,
    IN p_medical_department_id INT,
    IN p_resource_type VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Medical_resources
    SET medical_department_id = p_medical_department_id,
        resource_type = p_resource_type,
        description = p_description
    WHERE resources_id = p_resources_id;
END;

-- Eliminar Recurso Médico
CREATE PROCEDURE DeleteMedicalResource(
    IN p_resources_id INT
)
BEGIN
    DELETE FROM Medical_resources
    WHERE resources_id = p_resources_id;
END;
-- Procedimientos CRUD para la tabla Payment_type

-- Crear Tipo de Pago
CREATE PROCEDURE CreatePaymentType(
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Payment_type (name, description)
    VALUES (p_name, p_description);
END;

-- Leer Tipos de Pago
CREATE PROCEDURE GetPaymentTypes()
BEGIN
    SELECT * FROM Payment_type;
END;

-- Actualizar Tipo de Pago
CREATE PROCEDURE UpdatePaymentType(
    IN p_payment_type_id INT,
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Payment_type
    SET name = p_name,
        description = p_description
    WHERE payment_type_id = p_payment_type_id;
END;

-- Eliminar Tipo de Pago
CREATE PROCEDURE DeletePaymentType(
    IN p_payment_type_id INT
)
BEGIN
    DELETE FROM Payment_type
    WHERE payment_type_id = p_payment_type_id;
END;

-- Procedimientos CRUD para la tabla family_background

-- Crear Antecedente Familiar
CREATE PROCEDURE CreateFamilyBackground(
    IN p_patient_id INT,
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO family_background (patient_id, name, description)
    VALUES (p_patient_id, p_name, p_description);
END;

-- Leer Antecedentes Familiares
CREATE PROCEDURE GetFamilyBackgrounds()
BEGIN
    SELECT * FROM family_background;
END;

-- Actualizar Antecedente Familiar
CREATE PROCEDURE UpdateFamilyBackground(
    IN p_background_id INT,
    IN p_patient_id INT,
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE family_background
    SET patient_id = p_patient_id,
        name = p_name,
        description = p_description
    WHERE background_id = p_background_id;
END;

-- Eliminar Antecedente Familiar
CREATE PROCEDURE DeleteFamilyBackground(
    IN p_background_id INT
)
BEGIN
    DELETE FROM family_background
    WHERE background_id = p_background_id;
END;

-- Procedimientos CRUD para la tabla Medical_history

-- Crear Historia Médica
CREATE PROCEDURE CreateMedicalHistory(
    IN p_patient_id INT,
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Medical_history (patient_id, description)
    VALUES (p_patient_id, p_description);
END;

-- Leer Historias Médicas
CREATE PROCEDURE GetMedicalHistories()
BEGIN
    SELECT * FROM Medical_history;
END;

-- Actualizar Historia Médica
CREATE PROCEDURE UpdateMedicalHistory(
    IN p_h_medical_id INT,
    IN p_patient_id INT,
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Medical_history
    SET patient_id = p_patient_id,
        description = p_description
    WHERE h_medical_id = p_h_medical_id;
END;

-- Eliminar Historia Médica
CREATE PROCEDURE DeleteMedicalHistory(
    IN p_h_medical_id INT
)
BEGIN
    DELETE FROM Medical_history
    WHERE h_medical_id = p_h_medical_id;
END;

-- Procedimientos CRUD para la tabla Bills

-- Crear Factura
CREATE PROCEDURE CreateBill(
    IN p_appointment_id INT,
    IN p_payment_type_id INT,
    IN p_bill_date DATE,
    IN p_description TEXT,
    IN p_total DECIMAL(10, 2)
)
BEGIN
    INSERT INTO Bills (appointment_id, payment_type_id, bill_date, description, total)
    VALUES (p_appointment_id, p_payment_type_id, p_bill_date, p_description, p_total);
END;

-- Leer Facturas
CREATE PROCEDURE GetBills()
BEGIN
    SELECT * FROM Bills;
END;

-- Actualizar Factura
CREATE PROCEDURE UpdateBill(
    IN p_bill_id INT,
    IN p_appointment_id INT,
    IN p_payment_type_id INT,
    IN p_bill_date DATE,
    IN p_description TEXT,
    IN p_total DECIMAL(10, 2)
)
BEGIN
    UPDATE Bills
    SET appointment_id = p_appointment_id,
        payment_type_id = p_payment_type_id,
        bill_date = p_bill_date,
        description = p_description,
        total = p_total
    WHERE bill_id = p_bill_id;
END;

-- Eliminar Factura
CREATE PROCEDURE DeleteBill(
    IN p_bill_id INT
)
BEGIN
    DELETE FROM Bills
    WHERE bill_id = p_bill_id;
END;

-- Procedimientos CRUD para la tabla Medical_consultations

-- Crear Consulta Médica
CREATE PROCEDURE CreateMedicalConsultation(
    IN p_appointment_id INT,
    IN p_consultation_date DATE,
    IN p_symptoms VARCHAR(255),
    IN p_diagnosis VARCHAR(255),
    IN p_treatment VARCHAR(255)
)
BEGIN
    INSERT INTO Medical_consultations (appointment_id, consultation_date, symptoms, diagnosis, treatment)
    VALUES (p_appointment_id, p_consultation_date, p_symptoms, p_diagnosis, p_treatment);
END;

-- Leer Consultas Médicas
CREATE PROCEDURE GetMedicalConsultations()
BEGIN
    SELECT * FROM Medical_consultations;
END;

-- Actualizar Consulta Médica
CREATE PROCEDURE UpdateMedicalConsultation(
    IN p_consultation_id INT,
    IN p_appointment_id INT,
    IN p_consultation_date DATE,
    IN p_symptoms VARCHAR(255),
    IN p_diagnosis VARCHAR(255),
    IN p_treatment VARCHAR(255)
)
BEGIN
    UPDATE Medical_consultations
    SET appointment_id = p_appointment_id,
        consultation_date = p_consultation_date,
        symptoms = p_symptoms,
        diagnosis = p_diagnosis,
        treatment = p_treatment
    WHERE consultation_id = p_consultation_id;
END;

-- Eliminar Consulta Médica
CREATE PROCEDURE DeleteMedicalConsultation(
    IN p_consultation_id INT
)
BEGIN
    DELETE FROM Medical_consultations
    WHERE consultation_id = p_consultation_id;
END;

-- Procedimientos CRUD para la tabla Vital_signs

-- Crear Signo Vital
CREATE PROCEDURE CreateVitalSign(
    IN p_consultation_id INT,
    IN p_appointment_id INT,
    IN p_blood_pressure VARCHAR(255),
    IN p_heart_rate VARCHAR(255),
    IN p_breathing_frequency VARCHAR(255),
    IN p_temperature VARCHAR(255),
    IN p_weight VARCHAR(255),
    IN p_height VARCHAR(255)
)
BEGIN
    INSERT INTO Vital_signs (consultation_id, appointment_id, blood_pressure, heart_rate, breathing_frequency, temperature, weight, height)
    VALUES (p_consultation_id, p_appointment_id, p_blood_pressure, p_heart_rate, p_breathing_frequency, p_temperature, p_weight, p_height);
END;

-- Leer Signos Vitales
CREATE PROCEDURE GetVitalSigns()
BEGIN
    SELECT * FROM Vital_signs;
END;

-- Actualizar Signo Vital
CREATE PROCEDURE UpdateVitalSign(
    IN p_sign_id INT,
    IN p_consultation_id INT,
    IN p_appointment_id INT,
    IN p_blood_pressure VARCHAR(255),
    IN p_heart_rate VARCHAR(255),
    IN p_breathing_frequency VARCHAR(255),
    IN p_temperature VARCHAR(255),
    IN p_weight VARCHAR(255),
    IN p_height VARCHAR(255)
)
BEGIN
    UPDATE Vital_signs
    SET consultation_id = p_consultation_id,
        appointment_id = p_appointment_id,
        blood_pressure = p_blood_pressure,
        heart_rate = p_heart_rate,
        breathing_frequency = p_breathing_frequency,
        temperature = p_temperature,
        weight = p_weight,
        height = p_height
    WHERE sign_id = p_sign_id;
END;

-- Eliminar Signo Vital
CREATE PROCEDURE DeleteVitalSign(
    IN p_sign_id INT
)
BEGIN
    DELETE FROM Vital_signs
    WHERE sign_id = p_sign_id;
END;

-- Procedimientos CRUD para la tabla Analysis_type

-- Crear Tipo de Análisis
CREATE PROCEDURE CreateAnalysisType(
    IN p_analysis_type VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Analysis_type (analysis_type, description)
    VALUES (p_analysis_type, p_description);
END;

-- Leer Tipos de Análisis
CREATE PROCEDURE GetAnalysisTypes()
BEGIN
    SELECT * FROM Analysis_type;
END;

-- Actualizar Tipo de Análisis
CREATE PROCEDURE UpdateAnalysisType(
    IN p_analysis_id INT,
    IN p_analysis_type VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Analysis_type
    SET analysis_type = p_analysis_type,
        description = p_description
    WHERE analysis_id = p_analysis_id;
END;

-- Eliminar Tipo de Análisis
CREATE PROCEDURE DeleteAnalysisType(
    IN p_analysis_id INT
)
BEGIN
    DELETE FROM Analysis_type
    WHERE analysis_id = p_analysis_id;
END;

-- Procedimientos CRUD para la tabla Analysis_result

-- Crear Resultado de Análisis
CREATE PROCEDURE CreateAnalysisResult(
    IN p_consultation_id INT,
    IN p_appointment_id INT,
    IN p_result VARCHAR(255),
    IN p_observations VARCHAR(255)
)
BEGIN
    INSERT INTO Analysis_result (consultation_id, appointment_id, result, observations)
    VALUES (p_consultation_id, p_appointment_id, p_result, p_observations);
END;

-- Leer Resultados de Análisis
CREATE PROCEDURE GetAnalysisResults()
BEGIN
    SELECT * FROM Analysis_result;
END;

-- Actualizar Resultado de Análisis
CREATE PROCEDURE UpdateAnalysisResult(
    IN p_result_id INT,
    IN p_consultation_id INT,
    IN p_appointment_id INT,
    IN p_result VARCHAR(255),
    IN p_observations VARCHAR(255)
)
BEGIN
    UPDATE Analysis_result
    SET consultation_id = p_consultation_id,
        appointment_id = p_appointment_id,
        result = p_result,
        observations = p_observations
    WHERE result_id = p_result_id;
END;

-- Eliminar Resultado de Análisis
CREATE PROCEDURE DeleteAnalysisResult(
    IN p_result_id INT
)
BEGIN
    DELETE FROM Analysis_result
    WHERE result_id = p_result_id;
END;
-- Procedimientos CRUD para la tabla Treatment_type

-- Crear Tipo de Tratamiento
CREATE PROCEDURE CreateTreatmentType(
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Treatment_type (name, description)
    VALUES (p_name, p_description);
END;

-- Leer Tipos de Tratamiento
CREATE PROCEDURE GetTreatmentTypes()
BEGIN
    SELECT * FROM Treatment_type;
END;

-- Actualizar Tipo de Tratamiento
CREATE PROCEDURE UpdateTreatmentType(
    IN p_treatment_type_id INT,
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Treatment_type
    SET name = p_name,
        description = p_description
    WHERE treatment_type_id = p_treatment_type_id;
END;

-- Eliminar Tipo de Tratamiento
CREATE PROCEDURE DeleteTreatmentType(
    IN p_treatment_type_id INT
)
BEGIN
    DELETE FROM Treatment_type
    WHERE treatment_type_id = p_treatment_type_id;
END;

-- Procedimientos CRUD para la tabla Prescription

-- Crear Prescripción
CREATE PROCEDURE CreatePrescription(
    IN p_consultation_id INT,
    IN p_appointment_id INT,
    IN p_treatment_type_id INT,
    IN p_medicine VARCHAR(255),
    IN p_dose VARCHAR(255),
    IN p_instructions VARCHAR(255)
)
BEGIN
    INSERT INTO Prescription (consultation_id, appointment_id, treatment_type_id, medicine, dose, instructions)
    VALUES (p_consultation_id, p_appointment_id, p_treatment_type_id, p_medicine, p_dose, p_instructions);
END;

-- Leer Prescripciones
CREATE PROCEDURE GetPrescriptions()
BEGIN
    SELECT * FROM Prescription;
END;

-- Actualizar Prescripción
CREATE PROCEDURE UpdatePrescription(
    IN p_prescription_id INT,
    IN p_consultation_id INT,
    IN p_appointment_id INT,
    IN p_treatment_type_id INT,
    IN p_medicine VARCHAR(255),
    IN p_dose VARCHAR(255),
    IN p_instructions VARCHAR(255)
)
BEGIN
    UPDATE Prescription
    SET consultation_id = p_consultation_id,
        appointment_id = p_appointment_id,
        treatment_type_id = p_treatment_type_id,
        medicine = p_medicine,
        dose = p_dose,
        instructions = p_instructions
    WHERE prescription_id = p_prescription_id;
END;

-- Eliminar Prescripción
CREATE PROCEDURE DeletePrescription(
    IN p_prescription_id INT
)
BEGIN
    DELETE FROM Prescription
    WHERE prescription_id = p_prescription_id;
END;

-- Procedimientos CRUD para la tabla Medical_prescription_analysis

-- Crear Análisis de Prescripción Médica
CREATE PROCEDURE CreateMedicalPrescriptionAnalysis(
    IN p_prescription_id INT,
    IN p_analysis_result_id INT,
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Medical_prescription_analysis (prescription_id, analysis_result_id, description)
    VALUES (p_prescription_id, p_analysis_result_id, p_description);
END;

-- Leer Análisis de Prescripción Médica
CREATE PROCEDURE GetMedicalPrescriptionAnalyses()
BEGIN
    SELECT * FROM Medical_prescription_analysis;
END;

-- Actualizar Análisis de Prescripción Médica
CREATE PROCEDURE UpdateMedicalPrescriptionAnalysis(
    IN p_prescription_id INT,
    IN p_analysis_result_id INT,
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Medical_prescription_analysis
    SET description = p_description
    WHERE prescription_id = p_prescription_id
      AND analysis_result_id = p_analysis_result_id;
END;

-- Eliminar Análisis de Prescripción Médica
CREATE PROCEDURE DeleteMedicalPrescriptionAnalysis(
    IN p_prescription_id INT,
    IN p_analysis_result_id INT
)
BEGIN
    DELETE FROM Medical_prescription_analysis
    WHERE prescription_id = p_prescription_id
      AND analysis_result_id = p_analysis_result_id;
END;

-- Procedimientos CRUD para la tabla Insurance_company

-- Crear Compañía de Seguros
CREATE PROCEDURE CreateInsuranceCompany(
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Insurance_company (name, description)
    VALUES (p_name, p_description);
END;

-- Leer Compañías de Seguros
CREATE PROCEDURE GetInsuranceCompanies()
BEGIN
    SELECT * FROM Insurance_company;
END;

-- Actualizar Compañía de Seguros
CREATE PROCEDURE UpdateInsuranceCompany(
    IN p_company_id INT,
    IN p_name VARCHAR(255),
    IN p_description VARCHAR(255)
)
BEGIN
    UPDATE Insurance_company
    SET name = p_name,
        description = p_description
    WHERE company_id = p_company_id;
END;

-- Eliminar Compañía de Seguros
CREATE PROCEDURE DeleteInsuranceCompany(
    IN p_company_id INT
)
BEGIN
    DELETE FROM Insurance_company
    WHERE company_id = p_company_id;
END;

-- Procedimientos CRUD para la tabla Insurance

-- Crear Seguro
CREATE PROCEDURE CreateInsurance(
    IN p_patient_id INT,
    IN p_company_id INT,
    IN p_policy_number BIGINT,
    IN p_expiration DATE
)
BEGIN
    INSERT INTO Insurance (patient_id, company_id, policy_number, expiration)
    VALUES (p_patient_id, p_company_id, p_policy_number, p_expiration);
END;

-- Leer Seguros
CREATE PROCEDURE GetInsurances()
BEGIN
    SELECT * FROM Insurance;
END;

-- Actualizar Seguro
CREATE PROCEDURE UpdateInsurance(
    IN p_insurance_id INT,
    IN p_patient_id INT,
    IN p_company_id INT,
    IN p_policy_number BIGINT,
    IN p_expiration DATE
)
BEGIN
    UPDATE Insurance
    SET patient_id = p_patient_id,
        company_id = p_company_id,
        policy_number = p_policy_number,
        expiration = p_expiration
    WHERE insurance_id = p_insurance_id;
END;

-- Eliminar Seguro
CREATE PROCEDURE DeleteInsurance(
    IN p_insurance_id INT
)
BEGIN
    DELETE FROM Insurance
    WHERE insurance_id = p_insurance_id;
END;
