<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServiceModels
{
    protected $models = [
        'Analysis_result' => 'App\Models\AnalysisResult',
        'Medical_consultations' => 'App\Models\MedicalConsultations',
        'Medical_department' => 'App\Models\MedicalDepartment',
        'family_background' => 'App\Models\FamilyBackground',
        'Insurance_company' => 'App\Models\InsuranceCompany',
        'Insurance' => 'App\Models\Insurance',
        'Appointment_state' => 'App\Models\AppointmentState',
        'Specialty' => 'App\Models\Specialty',
        'Patients' => 'App\Models\Patient',
        'Appointments' => 'App\Models\Appointment',
        'Medical_staff' => 'App\Models\MedicalStaff',
        'Vital_signs' => 'App\Models\VitalSigns',
        'Medical_history' => 'App\Models\MedicalHistory',
        'Medical_resources' => 'App\Models\MedicalResources',
        'Medical_prescription_analysis' => 'App\Models\Medical_pres_anal',
        'Prescription' => 'App\Models\Prescription',
        'Payment_type' => 'App\Models\PaymentType',
        'Appointment_type' => 'App\Models\AppointmentType',
        'Analysis_type' => 'App\Models\Analysis_type',
        'Treatment_type' => 'App\Models\TreamentType',
        'Bills' => 'App\Models\Bill',
    ];

    protected $relationships = [
        'Analysis_result' => ['Appointment'],
        'Medical_consultations' => ['Appointment'],
        'Medical_department' => ['Medical_staff'],
        'family_background' => ['Patient'],
        'Insurance_company' => ['Insurance'],
        'Insurance' => ['Company', 'Patient'],
        'Appointment_state' => "",
        'Appointments' => ['Patient', 'Doctor', 'AppointmentState'],
        'Specialty' => ['Doctors'],
        'Patients' => ['FamilyBackground', 'Insurance'],
        'Appointment' => ['Patient', 'Medical_staff', 'Appointment_state'],
        'Medical_staff' => ['Specialty','Medical_department'],
        'Vital_signs' => ['Appointment'],
        'Medical_history' => "",
        'Medical_resources' => "",
        'Payment_type' => "",
        'Appointment_type' => ['Appointment'],
        'Analysis_type' => "",
        'Treatment_type' => "",
        'Bills' => ['Appointment'],
        'Medical_prescription_analysis' => ['Prescription', 'AnalysisResult'],
        'Prescription' => ['Consultation','TreatmentType'],
    ];

    protected $pluck = [
        'patient_id' => 'App\Models\Patient',
        'insurance_id' => 'App\Models\Insurance',
        'insurance_company_id' => 'App\Models\InsuranceCompany',
        'appointment_id' => 'App\Models\Appointment',
        'specialty_id' => 'App\Models\Specialty',
        'consultation_id' => 'App\Models\MedicalConsultations',
        'background_id' => 'App\Models\FamilyBackground',
        'medical_department_id' => 'App\Models\MedicalDepartment',
        'doctor_id' => 'App\Models\MedicalStaff',
        'appointment_state_id' => 'App\Models\AppointmentState',
        'payment_type_id' => 'App\Models\PaymentType',
        'treatment_type_id' => 'App\Models\TreamentType',
        'prescription_id' => 'App\Models\Prescription',
        'analysis_id' => 'App\Models\Analysis_type',
        'analysis_result_id' => 'App\Models\AnalysisResult',
    ];


    protected $model;

    public function __construct($model)
    {
        $this->model = $this->models[$model];
        $this->relationships = $this->relationships[$model];

    }





    public function getColumns()
    {
        return $this->model::getTableColumns();
    }


    public function setModel()
    {
        $data = $this->relationships ? $this->model::with($this->relationships)->get() : $this->model::get();
        $columns = $this->getColumns();
        $primaryKey = $this->getPK();

        $dataForm = array_filter($columns, function ($column) use ($primaryKey) {
            return $column !== $primaryKey;
        });
        $foreingKey = $this->getFKs($dataForm);

        $dataForm = $this->SetForm($dataForm, $foreingKey);
        return ['data' => $data, 'columns' => $columns, 'dataForm' => $dataForm, 'primaryKey' => $primaryKey];
    }

    public function insertData($data)
    {
        $this->model::create($data);
    }

    public function getPK()
    {
        $primaryKey = new $this->model;
        $primaryKey = $primaryKey->getKeyName();
        return $primaryKey;
    }

    public function getFKs($dataForm)
    {
        $foreignKeys = [];
        foreach ($dataForm as $column) {
            if (strpos($column, '_id') !== false) {
                $foreignKeys[] = $column;
            }
        }
        return $foreignKeys;
    }

    public function SetForm($dataForm, $foreingKey)
    {
        $form = [];
        foreach ($dataForm as  $item){
            if (in_array($item, $foreingKey)){
                $form[$item] = $this->getpluck($item);
            }else{
                $form[$item] = '';
            }
        }
        return $form;
    }

    public function getpluck($item)
    {
        switch ($item){
            case 'patient_id':
                return $this->pluck['patient_id']::pluck('name', 'patient_id');
            case 'insurance_id':
                return $this->pluck['insurance_id']::pluck('name', 'insurance_id');
            case 'company_id':
                return $this->pluck['insurance_company_id']::pluck('name', 'company_id');
            case 'appointment_id':
                return $this->pluck['appointment_id']::pluck('reason_for_appointment', 'appointment_id');
            case 'specialty_id':
                return $this->pluck['specialty_id']::pluck('name', 'specialty_id');
            case 'consultation_id':
                return $this->pluck['consultation_id']::pluck('symptoms', 'consultation_id');
            case 'background_id':
                return $this->pluck['background_id']::pluck('name', 'background_id');
            case 'medical_department_id':
                return $this->pluck['medical_department_id']::pluck('name', 'medical_department_id');
            case 'doctor_id':
                return $this->pluck['doctor_id']::pluck('name', 'medical_id');
            case 'appointment_state_id':
                return $this->pluck['appointment_state_id']::pluck('state', 'appointment_state_id');
            case 'payment_type_id':
                return $this->pluck['payment_type_id']::pluck('name', 'payment_type_id');
                case 'treatment_type_id':
                    return $this->pluck['treatment_type_id']::pluck('name', 'treatment_type_id');
                case 'prescription_id':
                    return $this->pluck['prescription_id']::pluck('medicine', 'prescription_id');
                case 'analysis_id':
                    return $this->pluck['analysis_id']::pluck('analysis_type', 'analysis_id');
                case 'analysis_result_id':
                    return $this->pluck['analysis_result_id']::pluck('result', 'analysis_result_id');
        }
    }

    public function updateData($id, $data)
    {
        $this->model::find($id)->update($data);
    }

    public function deleteData($id)
    {
        $this->model::find($id)->delete();
    }


}
