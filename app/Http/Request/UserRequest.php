<?php namespace App\Http\Request;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/23/2019
 * Time: 3:06 PM
 */

use App\Enums\StaticConfig;
class UserRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_code'=> 'required|unique:users,user_code',
            "name"	=>	"required|unique:users,name",
            "email"	=>	"email",
            "login_id"	=>	"required|unique:users,login_id",
            "password"	=>	"required",
            "display_order"	=>	"required|numeric|min:0|max:999999"
        ];
    }
    public function messages()
    {
        return [
            'user_code.required'=> StaticConfig::$Required,
            'user_code.unique'=> StaticConfig::$Unique,
            'name.required'=> StaticConfig::$Required,
            'name.unique'=> StaticConfig::$Unique,
            'email.email'=> StaticConfig::$Email,
            'login_id.required'=> StaticConfig::$Required,
            'login_id.unique'=> StaticConfig::$Unique,
            'password.required'=> StaticConfig::$Required,
            'display_order.required'=> StaticConfig::$Required,
            'display_order.numeric'=> StaticConfig::$Number,
            'display_order.min'=> StaticConfig::$Min,
            'display_order.max'=> StaticConfig::$Max,
        ];
    }

}
