<?php

namespace App\Repositories;

/**
 * Class RepositoryAbstract.
 *
 * @author Do Duy Duc <ducdd6647@co-well.com.vn>
 */
abstract class RepositoryAbstract extends AbstractValidator {

    /**
     * @param $string
     *
     * @return mixed
     */
    protected function slug($string) {
        return filter_var(str_replace(' ', '-', strtolower(trim($string))), FILTER_SANITIZE_URL);
    }

}
