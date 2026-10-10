<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutMethod;
use Facades\App\Services\CurrencyLayerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use App\Traits\Upload;

class PayoutMethodController extends Controller
{
    use Upload;

    public function index()
    {
        $data['payoutMethods'] = PayoutMethod::latest()->paginate(20);
        return view('admin.payout_methods.list', $data);
    }

    public function create()
    {
        $data['basicControl'] = basicControl();
        return view('admin.payout_methods.manual.create', $data);
    }

    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|min:3|max:50|unique:payout_methods',
            'description' => 'required|min:3|max:50',
            'field_name.*' => 'required|string',
            'input_type.*' => 'required|in:text,textarea,file,date,number',
            'is_required.*' => 'required|in:required,optional',
            'is_active' => 'nullable|integer|min:0|in:0,1',
            'payout_currencies' => 'required|array',
            'payout_currencies.*.currency_symbol' => 'required|string|max:255|regex:/^[A-Z\s]+$/',
            'payout_currencies.*.conversion_rate' => 'required|numeric',
            'payout_currencies.*.min_limit' => 'required|numeric',
            'payout_currencies.*.max_limit' => 'required|numeric',
            'payout_currencies.*.percentage_charge' => 'required|numeric',
            'payout_currencies.*.fixed_charge' => 'required|numeric',
            'image' => 'required|mimes:png,jpeg,gif|max:4098',
        ];


        $customMessages = [
            'field_name.*.required' => 'The form label field is required.',
            'input_type.*.required' => 'The input type field is required.',
            'is_required.*.required' => 'The required field is required.',
            'input_type.*.in' => 'The Input type is invalid.',
            'is_required.*.in' => 'The required value is invalid.',
            'payout_currencies.*.currency_symbol.required' => 'The payout currency  field is required.',
            'payout_currencies.*.currency_symbol.regex' => 'The payout currency field is invalid',
            'payout_currencies.*.conversion_rate.required' => 'The payout currency convention rate field is required.',
            'payout_currencies.*.conversion_rate.numeric' => 'The convention rate for payout currency must be a number.',
            'payout_currencies.*.min_limit.required' => 'The payout currency min limit field is required.',
            'payout_currencies.*.min_limit.numeric' => 'The min limit for payout currency must be a number.',
            'payout_currencies.*.max_limit.required' => 'The payout currency max limit field is required.',
            'payout_currencies.*.max_limit.numeric' => 'The max limit for payout currency must be a number.',
            'payout_currencies.*.percentage_charge.required' => 'The payout currency percentage charge field is required.',
            'payout_currencies.*.percentage_charge.numeric' => 'The percentage charge for payout currency must be a number.',
            'payout_currencies.*.fixed_charge.required' => 'The payout currency fixed charge is required.',
            'payout_currencies.*.fixed_charge.numeric' => 'The fixed charge for payout currency must be a number.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $inputForm = [];
        if ($request->has('field_name')) {
            for ($a = 0; $a < count($request->field_name); $a++) {
                $arr = array();
                $arr['field_name'] = clean($request->field_name[$a]);
                $arr['field_label'] = $request->field_name[$a];
                $arr['type'] = $request->input_type[$a];
                $arr['validation'] = $request->is_required[$a];
                $inputForm[$arr['field_name']] = $arr;
            }
        }

        if ($request->hasFile('image')) {
            try {
                $image = $this->fileUpload($request->image, config('filelocation.payoutMethod.path'), null, null, 'webp', 70);
                if ($image) {
                    $payoutGatewayImage = $image['path'];
                    $driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }

        $collection = collect($request->payout_currencies);
        $supportedCurrency = $collection->pluck('currency_symbol')->all();
        $response = PayoutMethod::create([
            'name' => $request->name,
            'code' => Str::slug($request->name),
            'description' => $request->description,
            'banks' => $request->banks ?? null,
            'supported_currency' => $supportedCurrency,
            'payout_currencies' => $request->payout_currencies,
            'is_active' => $request->status,
            'inputForm' => $inputForm,
            'logo' => $payoutGatewayImage ?? null,
            'driver' => $driver ?? null
        ]);

        if (!$response) {
            throw new Exception('Something went wrong.Please try again');
        }

        return redirect()->back()->with('success', 'Payout Method Successfully Saved');

    }

    public function edit($id)
    {
        $basicControl = basicControl();
        $payoutMethod = PayoutMethod::findOrFail($id);
        $banks = $payoutMethod->banks ?? [];
        return view('admin.payout_methods.edit', compact('payoutMethod', 'banks', 'basicControl'));
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|min:3|max:50|unique:payout_methods,name,' . $id,
            'description' => 'required|min:3|max:50',
            'field_name.*' => 'required|string',
            'input_type.*' => 'required|in:text,textarea,file,date,number',
            'is_required.*' => 'required|in:required,optional',
            'is_active' => 'nullable|integer|min:0|in:0,1',
            'payout_currencies' => 'required|array',
            'payout_currencies.*.currency_symbol' => 'required|string|max:255|regex:/^[A-Z\s]+$/',
            'payout_currencies.*.conversion_rate' => 'required|numeric',
            'payout_currencies.*.min_limit' => 'required|numeric',
            'payout_currencies.*.max_limit' => 'required|numeric',
            'payout_currencies.*.percentage_charge' => 'required|numeric',
            'payout_currencies.*.fixed_charge' => 'required|numeric',
            'image' => 'nullable|mimes:png,jpeg,gif|max:4096',
            'auto_update_currency' => 'nullable|integer|in:0,1'
        ];

        $customMessages = [
            'field_name.*.required' => 'The form label field is required.',
            'input_type.*.required' => 'The input type field is required.',
            'is_required.*.required' => 'The required field is required.',
            'input_type.*.in' => 'The Input type is invalid.',
            'is_required.*.in' => 'The required value is invalid.',
            'payout_currencies.*.currency_symbol.required' => 'The payout currency  field is required.',
            'payout_currencies.*.currency_symbol.regex' => 'The payout currency symbol must contain only uppercase letters.',
            'payout_currencies.*.conversion_rate.required' => 'The payout currency convention rate field is required.',
            'payout_currencies.*.conversion_rate.numeric' => 'The convention rate for payout currency must be a number.',
            'payout_currencies.*.min_limit.required' => 'The payout currency min limit field is required.',
            'payout_currencies.*.min_limit.numeric' => 'The min limit for payout currency must be a number.',
            'payout_currencies.*.max_limit.required' => 'The payout currency max limit field is required.',
            'payout_currencies.*.max_limit.numeric' => 'The max limit for payout currency must be a number.',
            'payout_currencies.*.percentage_charge.required' => 'The payout currency percentage charge field is required.',
            'payout_currencies.*.percentage_charge.numeric' => 'The percentage charge for payout currency must be a number.',
            'payout_currencies.*.fixed_charge.required' => 'The payout currency fixed charge is required.',
            'payout_currencies.*.fixed_charge.numeric' => 'The fixed charge for payout currency must be a number.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            $names = collect(request()->payout_currencies)
                ->filter(function ($item) {
                    return isset($item['name']) && $item['name'] !== null;
                })
                ->pluck('name')
                ->toArray();
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->input())
                ->with('selectedCurrencyList', $names);
        }

        $payoutMethod = PayoutMethod::findOrFail($id);

        $inputForm = [];
        if (isset($request->field_name)) {
            $inputs = [];
            for ($i = 0; $i < count($request->field_name); $i++) {
                $inputs['field_name'] = strtolower(Str::snake($request->field_name[$i]));
                $inputs['field_label'] = $request->field_name[$i];
                $inputs['type'] = $request->input_type[$i];
                $inputs['validation'] = $request->is_required[$i];
                $inputForm[$inputs['field_name']] = $inputs;
            }
        }

        $parameters = [];
        if ($payoutMethod->parameters) {
            foreach ($request->except('_token', '_method', 'image') as $k => $v) {
                foreach ($payoutMethod->parameters as $key => $cus) {
                    if ($k != $key) {
                        continue;
                    } else {
                        $rules[$key] = 'required|max:191';
                        $parameters[$key] = $v;
                    }
                }
            }
        }

        if ($request->hasFile('image')) {
            try {
                $image = $this->fileUpload($request->image, config('filelocation.payoutMethod.path'), null, null, 'webp', 70, $payoutMethod->logo, $payoutMethod->driver);
                if ($image) {
                    $gatewayImage = $image['path'];
                    $driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }


        $collection = collect($request->payout_currencies);
        if ($payoutMethod->is_automatic == 1) {
            $supportedCurrency = $collection->pluck('name')->all();
        } else {

            $supportedCurrency = $collection->pluck('currency_symbol')->all();
        }


        $response = $payoutMethod->update([
            'name' => $request->name,
            'banks' => $request->banks,
            'parameters' => $parameters,
            'description' => $request->description,
            'supported_currency' => $supportedCurrency,
            'payout_currencies' => $request->payout_currencies,
            'is_active' => $request->status,
            'is_auto_update' => $request->auto_update_currency,
            'environment' => $request->environment ?? 1,
            'inputForm' => $inputForm ?? $payoutMethod->inputForm,
            'logo' => $gatewayImage ?? $payoutMethod->logo,
            'driver' => $driver ?? $payoutMethod->driver,
        ]);

        if (!$response) {
            throw new Exception('Something went wrong.Please try again');
        }

        return redirect()->back()->with('success', 'Withdraw Method Updated Successfully');
    }


    public function activeDeactivate(Request $request)
    {
        try {
            $payoutMethod = PayoutMethod::where('code', $request->code)->firstOrFail();
            $payoutMethod->update([
                'is_active' => $payoutMethod->is_active == 1 ? 0 : 1
            ]);
            return back()->with('success', 'Payout method status updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function autoUpdate(Request $request, $id)
    {
        $updateForMethods = PayoutMethod::where('code', '!=', 'coinbase')
            ->where('is_automatic', 1)
            ->where('is_auto_update', 1)
            ->where('id', $id)->firstOrFail();

        $autoCurrencyUpdate = CurrencyLayerService::getCurrencyRate();

        $autoUp = [];
        foreach ($autoCurrencyUpdate->quotes as $key => $quote) {
            $strReplace = str_replace($autoCurrencyUpdate->source, '', $key);
            $autoUp[$strReplace] = $quote;
        }

        $usdToBase = 1.00;
        $currenciesArr = [];
        foreach ($updateForMethods->payout_currencies as $key => $payout_currency) {
            foreach ($payout_currency as $key1 => $item) {
                $resRate = $this->getCheck($payout_currency->name, $autoUp);
                $curRate = round($resRate / $usdToBase, 2);
                if ($resRate && $key1 == 'conversion_rate') {
                    $currenciesArr[$key][$key1] = $curRate;
                } else {
                    $currenciesArr[$key][$key1] = $item;
                }
            }
        }
        $updateForMethods->payout_currencies = $currenciesArr;
        $updateForMethods->save();
        return back()->with('success', 'Auto Currency Updated Successfully');
    }


    public function withdrawDays()
    {
        $data['withdrawalConfig'] = config("withdrawaldays");
        return view('admin.payout_methods.withdraw_days', $data);
    }

    public function withdrawDaysUpdate(Request $request)
    {
        $request->validate([
            "monday" => "nullable|integer|in:0,1",
            "tuesday" => "nullable|integer|in:0,1",
            "wednesday" => "nullable|integer|in:0,1",
            "thursday" => "nullable|integer|in:0,1",
            "friday" => "nullable|integer|in:0,1",
            "saturday" => "nullable|integer|in:0,1",
            "sunday" => "nullable|integer|in:0,1",
        ]);

        config(['withdrawaldays.Monday' => (int)$request->monday]);
        config(['withdrawaldays.Tuesday' => (int)$request->tuesday]);
        config(['withdrawaldays.Wednesday' => (int)$request->wednesday]);
        config(['withdrawaldays.Thursday' => (int)$request->thursday]);
        config(['withdrawaldays.Friday' => (int)$request->friday]);
        config(['withdrawaldays.Saturday' => (int)$request->saturday]);
        config(['withdrawaldays.Sunday' => (int)$request->sunday]);

        $fp = fopen(base_path() . '/config/withdrawaldays.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('withdrawaldays'), true) . ';');
        fclose($fp);

        Artisan::call('config:clear');

        return back()->with("success", "Successfully Updated");
    }

    public function getCheck($currency, $autoUp)
    {
        foreach ($autoUp as $key => $auto) {
            if ($key == $currency) {
                return $auto;
            }
        }
    }

}
