<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServiceModels
{
    protected $models = [
        'analysis_result' => 'App\Models\AnalysisResult',
        'medical_consultations' => 'App\Models\MedicalConsultations',
        'medical_department' => 'App\Models\MedicalDepartment',
        'family_background' => 'App\Models\FamilyBackground',
        'insurance_company' => 'App\Models\InsuranceCompany',
        'insurance' => 'App\Models\Insurance',
        'appointment_state' => 'App\Models\AppointmentState',
        'specialty' => 'App\Models\Specialty',
        'patients' => 'App\Models\Patient',
        'appointments' => 'App\Models\Appointment',
        'medical_staff' => 'App\Models\MedicalStaff',
        'vital_signs' => 'App\Models\VitalSigns',
        'medical_history' => 'App\Models\MedicalHistory',
        'medical_resources' => 'App\Models\MedicalResources',
        'medical_prescription_analysis' => 'App\Models\Medical_pres_anal',
        'prescription' => 'App\Models\Prescription',
        'payment_type' => 'App\Models\PaymentType',
        'appointment_type' => 'App\Models\AppointmentType',
        'analysis_type' => 'App\Models\Analysis_type',
        'treatment_type' => 'App\Models\TreamentType',
        'bills' => 'App\Models\Bill',
        'auditoría' => 'App\Models\Auditoria',
    ];

    protected $relationships = [
        'analysis_result' => ['appointment'],
        'medical_consultations' => ['appointment'],
        'medical_department' => ['medical_staff'],
        'family_background' => ['patient'],
        'insurance_company' => ['insurance'],
        'insurance' => ['company', 'patient'],
        'appointment_state' => "",
        'appointments' => ['patient', 'doctor', 'appointmentState'],
        'specialty' => ['doctors'],
        'patients' => ['familyBackground', 'insurance'],
        'appointment' => ['patient', 'medical_staff', 'appointment_state'],
        'medical_staff' => ['specialty','medical_department'],
        'vital_signs' => ['appointment'],
        'medical_history' => "",
        'medical_resources' => "",
        'payment_type' => "",
        'appointment_type' => ['appointment'],
        'analysis_type' => "",
        'treatment_type' => "",
        'bills' => ['appointment'],
        'medical_prescription_analysis' => ['prescription', 'analysisResult'],
        'prescription' => ['consultation','treatmentType'],
        
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
