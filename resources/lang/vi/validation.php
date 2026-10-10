<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute phải được chấp nhận.',
    'active_url'           => ':attribute không phải là một URL hợp lệ.',
    'after'                => ':attribute phải là ngày sau :date.',
    'after_or_equal'       => ':attribute phải là ngày sau hoặc bằng :date.',
    'alpha'                => ':attribute chỉ được chứa các chữ cái.',
    'alpha_dash'           => ':attribute chỉ được chứa chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num'            => ':attribute chỉ được chứa chữ cái và số.',
    'array'                => ':attribute phải là một mảng.',
    'before'               => ':attribute phải là ngày trước :date.',
    'before_or_equal'      => ':attribute phải là ngày trước hoặc bằng :date.',
    'between'              => [
        'numeric' => ':attribute phải nằm trong khoảng :min và :max.',
        'file'    => ':attribute phải có dung lượng từ :min đến :max kilobyte.',
        'string'  => ':attribute phải có từ :min đến :max ký tự.',
        'array'   => ':attribute phải có từ :min đến :max phần tử.',
    ],
    'boolean'              => ':attribute phải là true hoặc false.',
    'confirmed'            => ':attribute xác nhận không khớp.',
    'date'                 => ':attribute không phải là ngày hợp lệ.',
    'date_equals'          => ':attribute phải là ngày bằng :date.',
    'date_format'          => ':attribute không đúng định dạng :format.',
    'different'            => ':attribute và :other phải khác nhau.',
    'digits'               => ':attribute phải có :digits chữ số.',
    'digits_between'       => ':attribute phải có từ :min đến :max chữ số.',
    'dimensions'           => ':attribute có kích thước hình ảnh không hợp lệ.',
    'distinct'             => ':attribute có giá trị trùng lặp.',
    'email'                => ':attribute phải là địa chỉ email hợp lệ.',
    'ends_with'            => ':attribute phải kết thúc bằng một trong các giá trị: :values.',
    'exists'               => ':attribute được chọn không hợp lệ.',
    'file'                 => ':attribute phải là một tệp.',
    'filled'               => ':attribute không được để trống.',
    'gt'                   => [
        'numeric' => ':attribute phải lớn hơn :value.',
        'file'    => ':attribute phải lớn hơn :value kilobyte.',
        'string'  => ':attribute phải có nhiều hơn :value ký tự.',
        'array'   => ':attribute phải có nhiều hơn :value phần tử.',
    ],
    'gte'                  => [
        'numeric' => ':attribute phải lớn hơn hoặc bằng :value.',
        'file'    => ':attribute phải lớn hơn hoặc bằng :value kilobyte.',
        'string'  => ':attribute phải có từ :value ký tự trở lên.',
        'array'   => ':attribute phải có :value phần tử trở lên.',
    ],
    'image'                => ':attribute phải là hình ảnh.',
    'in'                   => ':attribute được chọn không hợp lệ.',
    'in_array'             => ':attribute không tồn tại trong :other.',
    'integer'              => ':attribute phải là số nguyên.',
    'ip'                   => ':attribute phải là địa chỉ IP hợp lệ.',
    'ipv4'                 => ':attribute phải là địa chỉ IPv4 hợp lệ.',
    'ipv6'                 => ':attribute phải là địa chỉ IPv6 hợp lệ.',
    'json'                 => ':attribute phải là chuỗi JSON hợp lệ.',
    'lt'                   => [
        'numeric' => ':attribute phải nhỏ hơn :value.',
        'file'    => ':attribute phải nhỏ hơn :value kilobyte.',
        'string'  => ':attribute phải có ít hơn :value ký tự.',
        'array'   => ':attribute phải có ít hơn :value phần tử.',
    ],
    'lte'                  => [
        'numeric' => ':attribute phải nhỏ hơn hoặc bằng :value.',
        'file'    => ':attribute phải nhỏ hơn hoặc bằng :value kilobyte.',
        'string'  => ':attribute phải có tối đa :value ký tự.',
        'array'   => ':attribute không được có nhiều hơn :value phần tử.',
    ],
    'max'                  => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'file'    => ':attribute không được lớn hơn :max kilobyte.',
        'string'  => ':attribute không được lớn hơn :max ký tự.',
        'array'   => ':attribute không được có nhiều hơn :max phần tử.',
    ],
    'mimes'                => ':attribute phải là tệp có kiểu: :values.',
    'mimetypes'            => ':attribute phải là tệp có kiểu: :values.',
    'min'                  => [
        'numeric' => ':attribute phải có giá trị tối thiểu :min.',
        'file'    => ':attribute phải có dung lượng tối thiểu :min kilobyte.',
        'string'  => ':attribute phải có tối thiểu :min ký tự.',
        'array'   => ':attribute phải có tối thiểu :min phần tử.',
    ],
    'not_in'               => ':attribute được chọn không hợp lệ.',
    'not_regex'            => ':attribute có định dạng không hợp lệ.',
    'numeric'              => ':attribute phải là số.',
    'password'             => 'Mật khẩu không đúng.',
    'present'              => ':attribute phải có mặt.',
    'regex'                => ':attribute có định dạng không hợp lệ.',
    'required'             => ':attribute không được để trống.',
    'required_if'          => ':attribute không được để trống khi :other là :value.',
    'required_unless'      => ':attribute không được để trống trừ khi :other thuộc :values.',
    'required_with'        => ':attribute không được để trống khi :values có mặt.',
    'required_with_all'    => ':attribute không được để trống khi :values đều có mặt.',
    'required_without'     => ':attribute không được để trống khi :values vắng mặt.',
    'required_without_all' => ':attribute không được để trống khi không có giá trị nào trong :values.',
    'same'                 => ':attribute và :other phải khớp nhau.',
    'size'                 => [
        'numeric' => ':attribute phải bằng :size.',
        'file'    => ':attribute phải có dung lượng :size kilobyte.',
        'string'  => ':attribute phải có :size ký tự.',
        'array'   => ':attribute phải chứa :size phần tử.',
    ],
    'starts_with'          => ':attribute phải bắt đầu bằng một trong các giá trị: :values.',
    'string'               => ':attribute phải là chuỗi.',
    'timezone'             => ':attribute phải là múi giờ hợp lệ.',
    'unique'               => ':attribute đã được sử dụng.',
    'uploaded'             => ':attribute tải lên thất bại.',
    'url'                  => ':attribute có định dạng không hợp lệ.',
    'uuid'                 => ':attribute phải là UUID hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
