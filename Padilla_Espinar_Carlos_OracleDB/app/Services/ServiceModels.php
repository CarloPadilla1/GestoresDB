<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServiceModels
{
    protected $models = [
        'ANALYSIS_RESULT' => 'App\Models\AnalysisResult',
        'MEDICAL_CONSULTATIONS' => 'App\Models\MedicalConsultations',
        'MEDICAL_DEPARTMENT' => 'App\Models\MedicalDepartment',
        'FAMILY_BACKGROUND' => 'App\Models\FamilyBackground',
        'INSURANCE_COMPANY' => 'App\Models\InsuranceCompany',
        'INSURANCE' => 'App\Models\Insurance',
        'APPOINTMENT_STATE' => 'App\Models\AppointmentState',
        'SPECIALTY' => 'App\Models\Specialty',
        'PATIENTS' => 'App\Models\Patient',
        'APPOINTMENTS' => 'App\Models\Appointment',
        'MEDICAL_STAFF' => 'App\Models\MedicalStaff',
        'VITAL_SIGNS' => 'App\Models\VitalSigns',
        'MEDICAL_HISTORY' => 'App\Models\MedicalHistory',
        'MEDICAL_RESOURCES' => 'App\Models\MedicalResources',
        'MEDICAL_PRESCRIPTION_ANALYSIS' => 'App\Models\Medical_pres_anal',
        'PRESCRIPTION' => 'App\Models\Prescription',
        'PAYMENT_TYPE' => 'App\Models\PaymentType',
        'APPOINTMENT_TYPE' => 'App\Models\AppointmentType',
        'ANALYSIS_TYPE' => 'App\Models\Analysis_type',
        'TREATMENT_TYPE' => 'App\Models\TreamentType',
        'BILLS' => 'App\Models\Bill',
    ];

    protected $relationships = [
        'ANALYSIS_RESULT' => ['appointment'],
        'MEDICAL_CONSULTATIONS' => ['appointment'],
        'MEDICAL_DEPARTMENT' => ['medical_staff'],
        'family_background' => ['patient'],
        'INSURANCE_COMPANY' => ['insurance'],
        'INSURANCE' => ['company', 'patient'],
        'APPOINTMENT_STATE' => "",
        'APPOINTMENTS' => ['patient', 'doctor', 'appointment_state'],
        'SPECIALTY' => ['doctors'],
        'PATIENTS' => ['family_background', 'insurance'],
        'APPOINTMENT' => ['patient', 'medical_staff', 'appointment_state'],
        'MEDICAL_STAFF' => ['specialty','medical_department'],
        'VITAL_SIGNS' => ['appointment'],
        'MEDICAL_HISTORY' => "",
        'MEDICAL_RESOURCES' => "",
        'PAYMENT_TYPE' => "",
        'APPOINTMENT_TYPE' => ['appointment'],
        'ANALYSIS_TYPE' => "",
        'TREATMENT_TYPE' => "",
        'BILLS' => ['appointment'],
        'MEDICAL_PRESCRIPTION_ANALYSIS' => ['prescription', 'analysis_result'],
        'PRESCRIPTION' => ['consultation','treatment_type'],
        'AUDITORÍA' => 'App\Models\Auditoria',

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
