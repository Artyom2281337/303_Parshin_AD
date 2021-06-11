<?php

use RedBeanPHP\R;

require_once '../app/dto/SpecialitiesDto.php';

class SpecialitiesRepository
{
    /**
     * @return SpecialitiesDto[]
     */
    public function findAllSpecialities(): array {
        $sql = '
select s.id as id, s.title as title   
from specialties as s';

        $rows = R::getAll($sql);
        $models = R::convertToBeans('specialties', $rows);

        return array_map(
            static function ($x) {
                $model = new SpecialitiesDto();
                $model->id = (int)$x->id;
                $model->title = $x->title;

                return $model;
            },
            $models
        );
    }
}