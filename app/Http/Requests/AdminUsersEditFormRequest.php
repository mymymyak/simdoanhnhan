<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Sentinel;

class AdminUsersEditFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // $user = Sentinel::getUser();
        // $routeID = ($this->route('profiles'));

        // return $user->id == $routeID;

        return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	    $userId = request()->segment(3);
        return [
            'account_type' => 'integer|between:1,4',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'first_name' => 'required',
            'last_name' => 'required',
        ];
    }
}
