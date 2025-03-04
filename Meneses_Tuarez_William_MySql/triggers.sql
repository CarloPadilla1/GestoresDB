DELIMITER //
CREATE TRIGGER before_analysis_result_insert
BEFORE INSERT ON analysis_result
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('analysis_result', 'INSERT', CONCAT(
        'consultation_id: ', NEW.consultation_id, ', ',
        'appointment_id: ', NEW.appointment_id, ', ',
        'result: ', NEW.result, ', ',
        'observations: ', NEW.observations
    ));
END;


CREATE TRIGGER before_analysis_result_update
BEFORE UPDATE ON analysis_result
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'analysis_result', 
        'UPDATE', 
        CONCAT(
            'consultation_id: ', OLD.consultation_id, ', ',
            'appointment_id: ', OLD.appointment_id, ', ',
            'result: ', OLD.result, ', ',
            'observations: ', OLD.observations
        ), 
        CONCAT(
            'consultation_id: ', NEW.consultation_id, ', ',
            'appointment_id: ', NEW.appointment_id, ', ',
            'result: ', NEW.result, ', ',
            'observations: ', NEW.observations
        )
    );
END;


CREATE TRIGGER before_analysis_result_delete
BEFORE DELETE ON analysis_result
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('analysis_result', 'DELETE', CONCAT(
        'consultation_id: ', OLD.consultation_id, ', ',
        'appointment_id: ', OLD.appointment_id, ', ',
        'result: ', OLD.result, ', ',
        'observations: ', OLD.observations
    ));
END;


CREATE TRIGGER before_analysis_type_insert
BEFORE INSERT ON analysis_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('analysis_type', 'INSERT', CONCAT(
        'analysis_type: ', NEW.analysis_type, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_analysis_type_update
BEFORE UPDATE ON analysis_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'analysis_type', 
        'UPDATE', 
        CONCAT(
            'analysis_type: ', OLD.analysis_type, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'analysis_type: ', NEW.analysis_type, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_analysis_type_delete
BEFORE DELETE ON analysis_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('analysis_type', 'DELETE', CONCAT(
        'analysis_type: ', OLD.analysis_type, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_appointments_insert
BEFORE INSERT ON appointments
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('appointments', 'INSERT', CONCAT(
        'patient_id: ', NEW.patient_id, ', ',
        'doctor_id: ', NEW.doctor_id, ', ',
        'appointment_state_id: ', NEW.appointment_state_id, ', ',
        'appointment_date: ', NEW.appointment_date, ', ',
        'appointment_time: ', NEW.appointment_time, ', ',
        'reason_for_appointment: ', NEW.reason_for_appointment, ', ',
        'observations: ', NEW.observations
    ));
END;


CREATE TRIGGER before_appointments_update
BEFORE UPDATE ON appointments
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'appointments', 
        'UPDATE', 
        CONCAT(
            'patient_id: ', OLD.patient_id, ', ',
            'doctor_id: ', OLD.doctor_id, ', ',
            'appointment_state_id: ', OLD.appointment_state_id, ', ',
            'appointment_date: ', OLD.appointment_date, ', ',
            'appointment_time: ', OLD.appointment_time, ', ',
            'reason_for_appointment: ', OLD.reason_for_appointment, ', ',
            'observations: ', OLD.observations
        ), 
        CONCAT(
            'patient_id: ', NEW.patient_id, ', ',
            'doctor_id: ', NEW.doctor_id, ', ',
            'appointment_state_id: ', NEW.appointment_state_id, ', ',
            'appointment_date: ', NEW.appointment_date, ', ',
            'appointment_time: ', NEW.appointment_time, ', ',
            'reason_for_appointment: ', NEW.reason_for_appointment, ', ',
            'observations: ', NEW.observations
        )
    );
END;


CREATE TRIGGER before_appointments_delete
BEFORE DELETE ON appointments
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('appointments', 'DELETE', CONCAT(
        'patient_id: ', OLD.patient_id, ', ',
        'doctor_id: ', OLD.doctor_id, ', ',
        'appointment_state_id: ', OLD.appointment_state_id, ', ',
        'appointment_date: ', OLD.appointment_date, ', ',
        'appointment_time: ', OLD.appointment_time, ', ',
        'reason_for_appointment: ', OLD.reason_for_appointment, ', ',
        'observations: ', OLD.observations
    ));
END;


CREATE TRIGGER before_appointment_state_insert
BEFORE INSERT ON appointment_state
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('appointment_state', 'INSERT', CONCAT(
        'state: ', NEW.state
    ));
END;


CREATE TRIGGER before_appointment_state_update
BEFORE UPDATE ON appointment_state
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'appointment_state', 
        'UPDATE', 
        CONCAT(
            'state: ', OLD.state
        ), 
        CONCAT(
            'state: ', NEW.state
        )
    );
END;


CREATE TRIGGER before_appointment_state_delete
BEFORE DELETE ON appointment_state
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('appointment_state', 'DELETE', CONCAT(
        'state: ', OLD.state
    ));
END;


CREATE TRIGGER before_bills_insert
BEFORE INSERT ON bills
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('bills', 'INSERT', CONCAT(
        'appointment_id: ', NEW.appointment_id, ', ',
        'payment_type_id: ', NEW.payment_type_id, ', ',
        'bill_date: ', NEW.bill_date, ', ',
        'description: ', NEW.description, ', ',
        'total: ', NEW.total
    ));
END;


CREATE TRIGGER before_bills_update
BEFORE UPDATE ON bills
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'bills', 
        'UPDATE', 
        CONCAT(
            'appointment_id: ', OLD.appointment_id, ', ',
            'payment_type_id: ', OLD.payment_type_id, ', ',
            'bill_date: ', OLD.bill_date, ', ',
            'description: ', OLD.description, ', ',
            'total: ', OLD.total
        ), 
        CONCAT(
            'appointment_id: ', NEW.appointment_id, ', ',
            'payment_type_id: ', NEW.payment_type_id, ', ',
            'bill_date: ', NEW.bill_date, ', ',
            'description: ', NEW.description, ', ',
            'total: ', NEW.total
        )
    );
END;


CREATE TRIGGER before_bills_delete
BEFORE DELETE ON bills
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('bills', 'DELETE', CONCAT(
        'appointment_id: ', OLD.appointment_id, ', ',
        'payment_type_id: ', OLD.payment_type_id, ', ',
        'bill_date: ', OLD.bill_date, ', ',
        'description: ', OLD.description, ', ',
        'total: ', OLD.total
    ));
END;

CREATE TRIGGER before_family_background_insert
BEFORE INSERT ON family_background
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('family_background', 'INSERT', CONCAT(
        'patient_id: ', NEW.patient_id, ', ',
        'name: ', NEW.name, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_family_background_update
BEFORE UPDATE ON family_background
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'family_background', 
        'UPDATE', 
        CONCAT(
            'patient_id: ', OLD.patient_id, ', ',
            'name: ', OLD.name, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'patient_id: ', NEW.patient_id, ', ',
            'name: ', NEW.name, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_family_background_delete
BEFORE DELETE ON family_background
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('family_background', 'DELETE', CONCAT(
        'patient_id: ', OLD.patient_id, ', ',
        'name: ', OLD.name, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_insurance_insert
BEFORE INSERT ON insurance
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('insurance', 'INSERT', CONCAT(
        'patient_id: ', NEW.patient_id, ', ',
        'company_id: ', NEW.company_id, ', ',
        'policy_number: ', NEW.policy_number, ', ',
        'expiration: ', NEW.expiration
    ));
END;


CREATE TRIGGER before_insurance_update
BEFORE UPDATE ON insurance
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'insurance', 
        'UPDATE', 
        CONCAT(
            'patient_id: ', OLD.patient_id, ', ',
            'company_id: ', OLD.company_id, ', ',
            'policy_number: ', OLD.policy_number, ', ',
            'expiration: ', OLD.expiration
        ), 
        CONCAT(
            'patient_id: ', NEW.patient_id, ', ',
            'company_id: ', NEW.company_id, ', ',
            'policy_number: ', NEW.policy_number, ', ',
            'expiration: ', NEW.expiration
        )
    );
END;


CREATE TRIGGER before_insurance_delete
BEFORE DELETE ON insurance
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('insurance', 'DELETE', CONCAT(
        'patient_id: ', OLD.patient_id, ', ',
        'company_id: ', OLD.company_id, ', ',
        'policy_number: ', OLD.policy_number, ', ',
        'expiration: ', OLD.expiration
    ));
END;

CREATE TRIGGER before_insurance_company_insert
BEFORE INSERT ON insurance_company
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('insurance_company', 'INSERT', CONCAT(
        'name: ', NEW.name, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_insurance_company_update
BEFORE UPDATE ON insurance_company
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'insurance_company', 
        'UPDATE', 
        CONCAT(
            'name: ', OLD.name, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'name: ', NEW.name, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_insurance_company_delete
BEFORE DELETE ON insurance_company
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('insurance_company', 'DELETE', CONCAT(
        'name: ', OLD.name, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_medical_consultations_insert
BEFORE INSERT ON medical_consultations
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('medical_consultations', 'INSERT', CONCAT(
        'appointment_id: ', NEW.appointment_id, ', ',
        'consultation_date: ', NEW.consultation_date, ', ',
        'symptoms: ', NEW.symptoms, ', ',
        'diagnosis: ', NEW.diagnosis, ', ',
        'treatment: ', NEW.treatment
    ));
END;


CREATE TRIGGER before_medical_consultations_update
BEFORE UPDATE ON medical_consultations
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'medical_consultations', 
        'UPDATE', 
        CONCAT(
            'appointment_id: ', OLD.appointment_id, ', ',
            'consultation_date: ', OLD.consultation_date, ', ',
            'symptoms: ', OLD.symptoms, ', ',
            'diagnosis: ', OLD.diagnosis, ', ',
            'treatment: ', OLD.treatment
        ), 
        CONCAT(
            'appointment_id: ', NEW.appointment_id, ', ',
            'consultation_date: ', NEW.consultation_date, ', ',
            'symptoms: ', NEW.symptoms, ', ',
            'diagnosis: ', NEW.diagnosis, ', ',
            'treatment: ', NEW.treatment
        )
    );
END;


CREATE TRIGGER before_medical_consultations_delete
BEFORE DELETE ON medical_consultations
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('medical_consultations', 'DELETE', CONCAT(
        'appointment_id: ', OLD.appointment_id, ', ',
        'consultation_date: ', OLD.consultation_date, ', ',
        'symptoms: ', OLD.symptoms, ', ',
        'diagnosis: ', OLD.diagnosis, ', ',
        'treatment: ', OLD.treatment
    ));
END;

CREATE TRIGGER before_medical_department_insert
BEFORE INSERT ON medical_department
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('medical_department', 'INSERT', CONCAT(
        'name: ', NEW.name, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_medical_department_update
BEFORE UPDATE ON medical_department
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'medical_department', 
        'UPDATE', 
        CONCAT(
            'name: ', OLD.name, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'name: ', NEW.name, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_medical_department_delete
BEFORE DELETE ON medical_department
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('medical_department', 'DELETE', CONCAT(
        'name: ', OLD.name, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_medical_history_insert
BEFORE INSERT ON medical_history
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('medical_history', 'INSERT', CONCAT(
        'patient_id: ', NEW.patient_id, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_medical_history_update
BEFORE UPDATE ON medical_history
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'medical_history', 
        'UPDATE', 
        CONCAT(
            'patient_id: ', OLD.patient_id, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'patient_id: ', NEW.patient_id, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_medical_history_delete
BEFORE DELETE ON medical_history
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('medical_history', 'DELETE', CONCAT(
        'patient_id: ', OLD.patient_id, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_medical_prescription_analysis_insert
BEFORE INSERT ON medical_prescription_analysis
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('medical_prescription_analysis', 'INSERT', CONCAT(
        'prescription_id: ', NEW.prescription_id, ', ',
        'analysis_result_id: ', NEW.analysis_result_id, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_medical_prescription_analysis_update
BEFORE UPDATE ON medical_prescription_analysis
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'medical_prescription_analysis', 
        'UPDATE', 
        CONCAT(
            'prescription_id: ', OLD.prescription_id, ', ',
            'analysis_result_id: ', OLD.analysis_result_id, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'prescription_id: ', NEW.prescription_id, ', ',
            'analysis_result_id: ', NEW.analysis_result_id, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_medical_prescription_analysis_delete
BEFORE DELETE ON medical_prescription_analysis
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('medical_prescription_analysis', 'DELETE', CONCAT(
        'prescription_id: ', OLD.prescription_id, ', ',
        'analysis_result_id: ', OLD.analysis_result_id, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_medical_resources_insert
BEFORE INSERT ON medical_resources
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('medical_resources', 'INSERT', CONCAT(
        'resource_type: ', NEW.resource_type, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_medical_resources_update
BEFORE UPDATE ON medical_resources
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'medical_resources', 
        'UPDATE', 
        CONCAT(
            'resource_type: ', OLD.resource_type, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'resource_type: ', NEW.resource_type, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_medical_resources_delete
BEFORE DELETE ON medical_resources
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('medical_resources', 'DELETE', CONCAT(
        'resource_type: ', OLD.resource_type, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_medical_staff_insert
BEFORE INSERT ON medical_staff
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('medical_staff', 'INSERT', CONCAT(
        'name: ', NEW.name, ', ',
        'lastname: ', NEW.lastname, ', ',
        'specialty_id: ', NEW.specialty_id, ', ',
        'department_id: ', NEW.medical_department_id, ', ',
        'phone_number: ', NEW.phone_number, ', ',
        'email: ', NEW.email
    ));
END;


CREATE TRIGGER before_medical_staff_update
BEFORE UPDATE ON medical_staff
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'medical_staff', 
        'UPDATE', 
        CONCAT(
            'name: ', OLD.name, ', ',
            'lastname: ', OLD.lastname, ', ',
            'specialty_id: ', OLD.specialty_id, ', ',
            'department_id: ', OLD.medical_department_id, ', ',
            'phone_number: ', OLD.phone_number, ', ',
            'email: ', OLD.email
        ), 
        CONCAT(
            'name: ', NEW.name, ', ',
            'lastname: ', NEW.lastname, ', ',
            'specialty_id: ', NEW.specialty_id, ', ',
            'department_id: ', NEW.medical_department_id, ', ',
            'phone_number: ', NEW.phone_number, ', ',
            'email: ', NEW.email
        )
    );
END;


CREATE TRIGGER before_medical_staff_delete
BEFORE DELETE ON medical_staff
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('medical_staff', 'DELETE', CONCAT(
        'name: ', OLD.name, ', ',
        'lastname: ', OLD.lastname, ', ',
        'specialty_id: ', OLD.specialty_id, ', ',
        'department_id: ', OLD.medical_department_id, ', ',
        'phone_number: ', OLD.phone_number, ', ',
        'email: ', OLD.email
    ));
END;

CREATE TRIGGER before_patients_insert
BEFORE INSERT ON patients
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('patients', 'INSERT', CONCAT(
        'name: ', NEW.name, ', ',
        'lastname: ', NEW.lastname, ', ',
        'age: ', NEW.age, ', ',
        'birthdate: ', NEW.birthdate, ', ',
        'gender: ', NEW.gender, ', ',
        'address: ', NEW.address, ', ',
        'phone_number: ', NEW.phone_number, ', ',
        'email: ', NEW.email
    ));
END;


CREATE TRIGGER before_patients_update
BEFORE UPDATE ON patients
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'patients', 
        'UPDATE', 
        CONCAT(
            'name: ', OLD.name, ', ',
            'lastname: ', OLD.lastname, ', ',
            'age: ', OLD.age, ', ',
            'birthdate: ', OLD.birthdate, ', ',
            'gender: ', OLD.gender, ', ',
            'address: ', OLD.address, ', ',
            'phone_number: ', OLD.phone_number, ', ',
            'email: ', OLD.email
        ), 
        CONCAT(
            'name: ', NEW.name, ', ',
            'lastname: ', NEW.lastname, ', ',
            'age: ', NEW.age, ', ',
            'birthdate: ', NEW.birthdate, ', ',
            'gender: ', NEW.gender, ', ',
            'address: ', NEW.address, ', ',
            'phone_number: ', NEW.phone_number, ', ',
            'email: ', NEW.email
        )
    );
END;


CREATE TRIGGER before_patients_delete
BEFORE DELETE ON patients
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('patients', 'DELETE', CONCAT(
        'name: ', OLD.name, ', ',
        'lastname: ', OLD.lastname, ', ',
        'age: ', OLD.age, ', ',
        'birthdate: ', OLD.birthdate, ', ',
        'gender: ', OLD.gender, ', ',
        'address: ', OLD.address, ', ',
        'phone_number: ', OLD.phone_number, ', ',
        'email: ', OLD.email
    ));
END;

CREATE TRIGGER before_payment_type_insert
BEFORE INSERT ON payment_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('payment_type', 'INSERT', CONCAT(
        'type: ', NEW.name, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_payment_type_update
BEFORE UPDATE ON payment_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'payment_type', 
        'UPDATE', 
        CONCAT(
            'type: ', OLD.name, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'type: ', NEW.name, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_payment_type_delete
BEFORE DELETE ON payment_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('payment_type', 'DELETE', CONCAT(
        'type: ', OLD.name, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_prescription_insert
BEFORE INSERT ON prescription
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('prescription', 'INSERT', CONCAT(
        'consultation_id: ', NEW.consultation_id, ', ',
        'medicine: ', NEW.medicine, ', ',
        'dose: ', NEW.dose, ', ',
        'treatment_type: ', NEW.treatment_type_id, ', ',
        'appointmen: ', NEW.appointment_id, ', ',
        'instructions: ', NEW.instructions
    ));
END;


CREATE TRIGGER before_prescription_update
BEFORE UPDATE ON prescription
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'prescription', 
        'UPDATE', 
        CONCAT(
            'consultation_id: ', OLD.consultation_id, ', ',
            'medicine: ', OLD.medicine, ', ',
            'dose: ', OLD.dose, ', ',
        'treatment_type: ', NEW.treatment_type_id, ', ',
        'appointmen: ', NEW.appointment_id, ', ',
            'instructions: ', OLD.instructions
        ), 
        CONCAT(
            'consultation_id: ', NEW.consultation_id, ', ',
            'medicine: ', NEW.medicine, ', ',
            'dose: ', NEW.dose, ', ',
        'treatment_type: ', NEW.treatment_type_id, ', ',
        'appointmen: ', NEW.appointment_id, ', ',
            'instructions: ', NEW.instructions
        )
    );
END;


CREATE TRIGGER before_prescription_delete
BEFORE DELETE ON prescription
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('prescription', 'DELETE', CONCAT(
        'consultation_id: ', OLD.consultation_id, ', ',
        'medicine: ', OLD.medicine, ', ',
        'dose: ', OLD.dose, ', ',
        'treatment_type: ', NEW.treatment_type_id, ', ',
        'appointmen: ', NEW.appointment_id, ', ',
        'instructions: ', OLD.instructions
    ));
END;

CREATE TRIGGER before_specialty_insert
BEFORE INSERT ON specialty
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('specialty', 'INSERT', CONCAT(
        'name: ', NEW.name, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_specialty_update
BEFORE UPDATE ON specialty
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'specialty', 
        'UPDATE', 
        CONCAT(
            'name: ', OLD.name, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'name: ', NEW.name, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_specialty_delete
BEFORE DELETE ON specialty
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('specialty', 'DELETE', CONCAT(
        'name: ', OLD.name, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_treatment_type_insert
BEFORE INSERT ON treatment_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('treatment_type', 'INSERT', CONCAT(
        'name: ', NEW.name, ', ',
        'description: ', NEW.description
    ));
END;


CREATE TRIGGER before_treatment_type_update
BEFORE UPDATE ON treatment_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'treatment_type', 
        'UPDATE', 
        CONCAT(
            'name: ', OLD.name, ', ',
            'description: ', OLD.description
        ), 
        CONCAT(
            'name: ', NEW.name, ', ',
            'description: ', NEW.description
        )
    );
END;


CREATE TRIGGER before_treatment_type_delete
BEFORE DELETE ON treatment_type
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('treatment_type', 'DELETE', CONCAT(
        'name: ', OLD.name, ', ',
        'description: ', OLD.description
    ));
END;

CREATE TRIGGER before_vital_signs_insert
BEFORE INSERT ON vital_signs
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, new_values)
    VALUES ('vital_signs', 'INSERT', CONCAT(
        'consultation_id: ', NEW.consultation_id, ', ',
        'appointment_id: ', NEW.appointment_id, ', ',
        'blood_pressure: ', NEW.blood_pressure, ', ',
        'heart_rate: ', NEW.heart_rate, ', ',
        'breathing_frequency: ', NEW.breathing_frequency, ', ',
        'temperature: ', NEW.temperature, ', ',
        'weight: ', NEW.weight, ', ',
        'height: ', NEW.height
    ));
END;


CREATE TRIGGER before_vital_signs_update
BEFORE UPDATE ON vital_signs
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values, new_values)
    VALUES (
        'vital_signs', 
        'UPDATE', 
        CONCAT(
            'consultation_id: ', OLD.consultation_id, ', ',
            'appointment_id: ', OLD.appointment_id, ', ',
            'blood_pressure: ', OLD.blood_pressure, ', ',
            'heart_rate: ', OLD.heart_rate, ', ',
            'breathing_frequency: ', OLD.breathing_frequency, ', ',
            'temperature: ', OLD.temperature, ', ',
            'weight: ', OLD.weight, ', ',
            'height: ', OLD.height
        ), 
        CONCAT(
            'consultation_id: ', NEW.consultation_id, ', ',
            'appointment_id: ', NEW.appointment_id, ', ',
            'blood_pressure: ', NEW.blood_pressure, ', ',
            'heart_rate: ', NEW.heart_rate, ', ',
            'breathing_frequency: ', NEW.breathing_frequency, ', ',
            'temperature: ', NEW.temperature, ', ',
            'weight: ', NEW.weight, ', ',
            'height: ', NEW.height
        )
    );
END;


CREATE TRIGGER before_vital_signs_delete
BEFORE DELETE ON vital_signs
FOR EACH ROW
BEGIN
    INSERT INTO Auditoría (table_name, operation, old_values)
    VALUES ('vital_signs', 'DELETE', CONCAT(
        'consultation_id: ', OLD.consultation_id, ', ',
        'appointment_id: ', OLD.appointment_id, ', ',
        'blood_pressure: ', OLD.blood_pressure, ', ',
        'heart_rate: ', OLD.heart_rate, ', ',
        'breathing_frequency: ', OLD.breathing_frequency, ', ',
        'temperature: ', OLD.temperature, ', ',
        'weight: ', OLD.weight, ', ',
        'height: ', OLD.height
    ));
END;
//