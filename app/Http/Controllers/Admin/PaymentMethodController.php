<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    use Upload;

    public function index()
    {
        $data['paymentGateways'] = Gateway::automatic()->orderBy('sort_by', 'ASC')->get();
        return view('admin.payment_methods.list', $data);
    }

    public function sortPaymentMethods(Request $request)
    {
        $sortItems = $request->sort;
        foreach ($sortItems as $key => $value) {
            Gateway::where('code', $value)->update(['sort_by' => $key + 1]);
        }
    }

    public function edit($id)
    {
        try {
            $data['basicControl'] = basicControl();
            $data['method'] = Gateway::findOrFail($id);
            return view('admin.payment_methods.edit', $data);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'receivable_currencies' => 'required|array',
            'receivable_currencies.*.name' => 'required|string',
            'receivable_currencies.*.currency_symbol' => 'required|string|max:255',
            'receivable_currencies.*.conversion_rate' => 'required|numeric',
            'receivable_currencies.*.min_limit' => 'required|numeric',
            'receivable_currencies.*.max_limit' => 'required|numeric',
            'receivable_currencies.*.percentage_charge' => 'required|numeric',
            'receivable_currencies.*.fixed_charge' => 'required|numeric',
            'description' => 'required|string|min:3',
            'is_active' => 'nullable|integer|in:0,1',
            'test_environment' => 'sometimes|required|string|in:test,live',
            'image' => 'nullable|mimes:png,jpeg,gif|max:4096',
        ];

        $customMessages = [
            'receivable_currencies.*.currency_symbol.required' => 'The receivable currency currency symbol field is required.',
            'receivable_currencies.*.conversion_rate.required' => 'The receivable currency convention rate field is required.',
            'receivable_currencies.*.conversion_rate.numeric' => 'The convention rate for receivable currency must be a number.',
            'receivable_currencies.*.min_limit.required' => 'The receivable currency min limit field is required.',
            'receivable_currencies.*.min_limit.numeric' => 'The min limit for receivable currency must be a number.',
            'receivable_currencies.*.max_limit.required' => 'The receivable currency max limit field is required.',
            'receivable_currencies.*.max_limit.numeric' => 'The max limit for receivable currency must be a number.',
            'receivable_currencies.*.percentage_charge.required' => 'The receivable currency percentage charge field is required.',
            'receivable_currencies.*.percentage_charge.numeric' => 'The percentage charge for receivable currency must be a number.',
            'receivable_currencies.*.fixed_charge.required' => 'The receivable currency fixed charge name is required.',
            'receivable_currencies.*.fixed_charge.numeric' => 'The fixed charge for receivable currency must be a number.',
        ];


        $gateway = Gateway::where('id', $id)->firstOr(function () {
            throw new Exception('No payment method found');
        });

        $parameters = [];
        foreach ($request->except('_token', '_method', 'image') as $k => $v) {
            foreach ($gateway->parameters as $key => $cus) {
                if ($k != $key) {
                    continue;
                } else {
                    $rules[$key] = 'required|max:191';
                    $parameters[$key] = $v;
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            $names = collect(request()->receivable_currencies)
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

        if ($request->hasFile('image')) {
            try {
                $image = $this->fileUpload($request->image, config('filelocation.gateway.path'), null, null, 'webp', 70, $gateway->image, $gateway->driver);
                if ($image) {
                    $gatewayImage = $image['path'];
                    $driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }

        try {
            $collection = collect($request->receivable_currencies);
            $supportedCurrency = $collection->pluck('name')->all();
            $response = $gateway->update([
                'supported_currency' => $supportedCurrency,
                'receivable_currencies' => $request->receivable_currencies,
                'description' => $request->description,
                'parameters' => $parameters,
                'image' => $gatewayImage ?? $gateway->image,
                'driver' => $driver ?? $gateway->driver,
                'environment' => $request->test_environment ?? null,
                'status' => $request->is_active
            ]);

            if (!$response) {
                throw new \Exception('Unexpected error! Please try again.');
            }
            return back()->with('success', 'Gateway data has been updated.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }


    public function deactivate(Request $request)
    {
        try {
            $gateway = Gateway::where('code', $request->code)->firstOrFail();
            $gateway->update([
                'status' => $gateway->status == 1 ? 0 : 1
            ]);
            return back()->with('success', 'Gateway status updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
