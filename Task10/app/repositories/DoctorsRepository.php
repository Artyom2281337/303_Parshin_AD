<?php

use RedBeanPHP\R;

require_once '../app/dto/DoctorId.php';
require_once '../app/dto/DoctorFullModel.php';
require_once '../app/dto/DoctorCreateModel.php';
require_once '../app/dto/DoctorUpdateModel.php';

class DoctorsRepository
{
    /**
     * @return array
     */
    public function findAllIds(): array
    {
        $models = R::findAll('doctors');

        return array_map(
            static function ($x) {
                $model = new DoctorId();
                $model->id = (int)$x->id;

                return $model;
            },
            $models
        );
    }

    /**
     * @return DoctorFullModel[]
     */
    public function findAll(): array
    {
        $sql = '
select d.id,
       d.first_name,
       d.last_name,
       d.patronymic,
       d.date_of_birth,
       d.earning_in_percents,
       s.title as specialty,
       d.speciality_id,
       es.title,
       es.id as statusId
from doctors as d
         join specialties s on d.speciality_id = s.id
         join employee_statuses es on d.employee_status_id = es.id';

        $rows = R::getAll($sql);
        $receptions = R::convertToBeans('doctors', $rows);

        return array_map(
            static function ($x) {
                $model = new DoctorFullModel();
                $model->id = (int)$x->id;
                $model->firstName = $x->first_name;
                $model->lastName = $x->last_name;
                $model->patronymic = $x->patronymic;
                $model->dateOfBirth = $x->date_of_birth;
                $model->earning = (int)$x->earning_in_percents;
                $model->speciality = $x->specialty;
                $model->statusId = (int)$x->statusId;
                $model->status = $x->title;
                $model->specialityId = (int)$x->speciality_id;

                return $model;
            },
            $receptions
        );
    }

    public function create(DoctorCreateModel $model): int
    {
        $doctor = R::dispense('doctors');
        $doctor->first_name = $model->firstName;
        $doctor->last_name = $model->lastName;
        $doctor->patronymic = $model->patronymic;
        $doctor->date_of_birth = $model->dateOfBirth;
        $doctor->speciality_id = $model->specialityId;
        $doctor->earning_in_percents = $model->earningInPercents;
        $doctor->employee_status_id = 1; // new

        return R::store($doctor);
    }

    public function deleteById(int $id)
    {
        R::trash('doctors', $id);
    }

    public function update(DoctorUpdateModel $model): void
    {
        $doctor = R::load('doctors', $model->id);
        $doctor->first_name = $model->firstName;
        $doctor->last_name = $model->lastName;
        $doctor->patronymic = $model->patronymic;
        $doctor->date_of_birth = $model->dateOfBirth;
        $doctor->speciality_id = $model->specialityId;
        $doctor->employee_status_id = $model->statusId;
        $doctor->earning_in_percents = $model->earningInPercents;
        $doctor->status_id = 1;

        R::store($doctor);
    }
}
