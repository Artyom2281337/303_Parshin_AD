<?php

use RedBeanPHP\R;

require_once '../app/dto/EmployeeStatusesDto.php';

class EmployeeStatusesRepository
{
    /**
     * @return EmployeeStatusesDto[]
     */
    public function findAllEmployeeStatuses(): array {
        $models = R::findAll('employee_statuses');

        return array_map(
            static function ($x) {
                $model = new EmployeeStatusesDto();
                $model->id = (int)$x->id;
                $model->title = $x->title;

                return $model;
            },
            $models
        );
    }
}