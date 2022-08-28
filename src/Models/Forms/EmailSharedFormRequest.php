<?php

namespace WalkerChiu\SiteCMS\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use WalkerChiu\Core\Models\Forms\FormRequest;

class EmailSharedFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        if (
            $request->isMethod('put')
            && empty($data['id'])
            && isset($request->id)
        ) {
            $data['id'] = (int) $request->id;
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return [
            'email_register_id' => trans('php-site-cms::email.emailType.register'),
            'email_login_id'    => trans('php-site-cms::email.emailType.login'),
            'email_reset_id'    => trans('php-site-cms::email.emailType.reset'),
            'email_checkout_id' => trans('php-site-cms::email.emailType.checkout'),
            'email_order_id'    => trans('php-site-cms::email.emailType.order'),
            'theme'             => trans('php-site-cms::site.email_theme'),
            'style'             => trans('php-site-cms::site.email_style'),
            'header'            => trans('php-site-cms::site.email_header'),
            'footer'            => trans('php-site-cms::site.email_footer')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        $rules = [
            'email_register_id' => ['nullable', 'integer', 'min:1', 'exists:'.config('wk-core.table.site-cms.emails').',id'],
            'email_login_id'    => ['nullable', 'integer', 'min:1', 'exists:'.config('wk-core.table.site-cms.emails').',id'],
            'email_reset_id'    => ['nullable', 'integer', 'min:1', 'exists:'.config('wk-core.table.site-cms.emails').',id'],
            'email_checkout_id' => ['nullable', 'integer', 'min:1', 'exists:'.config('wk-core.table.site-cms.emails').',id'],
            'email_order_id'    => ['nullable', 'integer', 'min:1', 'exists:'.config('wk-core.table.site-cms.emails').',id'],
            'theme'  => '',
            'style'  => '',
            'header' => '',
            'footer' => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','integer','min:1','exists:'.config('wk-core.table.site-cms.emails').',id']]);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.required'               => trans('php-core::validation.required'),
            'id.integer'                => trans('php-core::validation.integer'),
            'id.min'                    => trans('php-core::validation.min'),
            'id.exists'                 => trans('php-core::validation.exists'),
            'email_register_id.integer' => trans('php-core::validation.integer'),
            'email_register_id.min'     => trans('php-core::validation.min'),
            'email_register_id.exists'  => trans('php-core::validation.exists'),
            'email_login_id.integer'    => trans('php-core::validation.integer'),
            'email_login_id.min'        => trans('php-core::validation.min'),
            'email_login_id.exists'     => trans('php-core::validation.exists'),
            'email_reset_id.integer'    => trans('php-core::validation.integer'),
            'email_reset_id.min'        => trans('php-core::validation.min'),
            'email_reset_id.exists'     => trans('php-core::validation.exists'),
            'email_checkout_id.integer' => trans('php-core::validation.integer'),
            'email_checkout_id.min'     => trans('php-core::validation.min'),
            'email_checkout_id.exists'  => trans('php-core::validation.exists'),
            'email_order_id.integer'    => trans('php-core::validation.integer'),
            'email_order_id.min'        => trans('php-core::validation.min'),
            'email_order_id.exists'     => trans('php-core::validation.exists')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
    }
}
