<?php

namespace WalkerChiu\SiteCMS\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use WalkerChiu\Core\Models\Forms\FormRequest;

class SiteBasicFormRequest extends FormRequest
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
            'type'               => trans('php-site-cms::site.type'),
            'serial'             => trans('php-site-cms::site.serial'),
            'identifier'         => trans('php-site-cms::site.identifier'),
            'language'           => trans('php-site-cms::site.language'),
            'language_supported' => trans('php-site-cms::site.language_supported'),
            'timezone'           => trans('php-site-cms::site.timezone'),
            'view_template'      => trans('php-site-cms::site.view_template'),
            'email_template'     => trans('php-site-cms::site.email_template'),
            'skin'               => trans('php-site-cms::site.skin'),
            'script_head'        => trans('php-site-cms::site.script_head'),
            'script_footer'      => trans('php-site-cms::site.script_footer'),
            'options'            => trans('php-site-cms::site.options'),
            'can_guestComment'   => trans('php-site-cms::site.can_guestComment'),
            'is_main'            => trans('php-site-cms::site.is_main'),
            'is_enabled'         => trans('php-site-cms::site.is_enabled'),

            'name'               => trans('php-site-cms::site.name'),
            'description'        => trans('php-site-cms::site.description'),
            'keywords'           => trans('php-site-cms::site.keywords'),
            'remarks'            => trans('php-site-cms::site.remarks')
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
            'type'               => '',
            'serial'             => '',
            'identifier'         => 'required|string|max:255',
            'language'           => ['required', Rule::in(config('wk-core.class.core.language')::getCodes())],
            'language_supported' => 'required|array',
            'timezone'           => ['required', Rule::in(config('wk-core.class.core.timeZone')::getValues())],
            'view_template'      => '',
            'email_template'     => '',
            'skin'               => '',
            'script_head'        => '',
            'script_footer'      => '',
            'options'            => 'nullable|json',
            'can_guestComment'   => 'boolean',
            'is_main'            => 'boolean',
            'is_enabled'         => 'boolean',

            'name'               => 'required|string|max:255',
            'description'        => '',
            'keywords'           => '',
            'remarks'            => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','integer','min:1','exists:'.config('wk-core.table.site-cms.sites').',id']]);
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
            'id.required'                 => trans('php-core::validation.required'),
            'id.integer'                  => trans('php-core::validation.integer'),
            'id.min'                      => trans('php-core::validation.min'),
            'id.exists'                   => trans('php-core::validation.exists'),
            'identifier.required'         => trans('php-core::validation.required'),
            'identifier.max'              => trans('php-core::validation.max'),
            'language.required'           => trans('php-core::validation.required'),
            'language.in'                 => trans('php-core::validation.in'),
            'language_supported.required' => trans('php-core::validation.required'),
            'language_supported.array'    => trans('php-core::validation.array'),
            'timezone.required'           => trans('php-core::validation.required'),
            'timezone.in'                 => trans('php-core::validation.in'),
            'options.json'                => trans('php-core::validation.json'),
            'can_guestComment.boolean'    => trans('php-core::validation.boolean'),
            'is_main.boolean'             => trans('php-core::validation.boolean'),
            'is_enabled.boolean'          => trans('php-core::validation.boolean'),

            'name.required'       => trans('php-core::validation.required'),
            'name.string'         => trans('php-core::validation.string'),
            'name.max'            => trans('php-core::validation.max')
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
        $validator->after( function ($validator) {
            $data = $validator->getData();
            if (
                isset($data['language_supported'])
                && is_array($data['language_supported'])
            ) {
                foreach ($data['language_supported'] as $item) {
                    if (!in_array($item, config('wk-core.class.core.language')::getCodes()))
                        $validator->errors()->add('language_supported', trans('php-core::validation.in'));
                }
            }
            if (isset($data['identifier'])) {
                $result = config('wk-core.class.site-cms.site')::where('identifier', $data['identifier'])
                                ->when(isset($data['id']), function ($query) use ($data) {
                                    return $query->where('id', '<>', $data['id']);
                                  })
                                ->exists();
                if ($result)
                    $validator->errors()->add('identifier', trans('php-core::validation.unique', ['attribute' => trans('php-site-cms::site.identifier')]));
            }
        });
    }
}
