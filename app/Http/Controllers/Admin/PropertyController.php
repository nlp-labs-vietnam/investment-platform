<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Amenity;
use App\Models\Favourite;
use App\Models\Image;
use App\Models\ManageTime;
use App\Models\Property;
use App\Models\PropertyRevision;
use App\Models\PropertySeo;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PropertyController extends Controller
{
    use Upload;

    public function properties($type = 'all')
    {
        return view('admin.property.properties', compact('type'));
    }

    public function propertySearch(Request $request, $type)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];

        $filterProperty = $request->filterProperty;
        $filterStatus = $request->filterStatus;

        $properties = Property::when($type == "upcoming", function ($query) {
            $query->where('start_date', '>', now());
        })
            ->when($type == "running", function ($query) {
                $query->where('expire_date', '>', now())->where('start_date', '<', now());
            })
            ->when($type == "expired", function ($query) {
                $query->where('expire_date', '<', now());
            })
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->where('title', 'LIKE', "%{$search}%");
            })
            ->when(!empty($filterProperty), function ($query) use ($filterProperty) {
                $query->where('title', 'LIKE', "%{$filterProperty}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->orderBy('id', 'ASC');


        return DataTables::of($properties)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('property', function ($item) {
                return Str::limit($item->title, 50);
            })
            ->addColumn('investment_amount', function ($item) {
                return $item->investmentAmount;
            })
            ->addColumn('target_investment', function ($item) {
                return currencyPosition($item->total_investment_amount);
            })
            ->addColumn('profit', function ($item) {
                return $item->profit_type == 1 ? fractionNumber($item->profit) . '%' : currencyPosition($item->profit);
            })
            ->addColumn('installment', function ($item) {
                if ($item->is_installment == 0) {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="bg-danger"></span>' . trans('No') . '
                  </span>';
                } else {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Yes') . '
                  </span>';
                }
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('In Active') . '
                  </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $editRoute = route('admin.property.edit', $item->id);
                $deleteRoute = route('admin.property.delete', $item->id);
                $InvestmentDetailsRoute = route('admin.property.details', $item->id);

                $update = '';
                $delete = '';

                if (adminAccessRoute(array_merge(config('permissionList.Manage_Property.Properties.permission.edit'), config('permissionList.Manage_Property.Properties.permission.delete')))) {
                    if (adminAccessRoute(config('permissionList.Manage_Property.Properties.permission.edit'))) {
                        $update = "<a class='btn btn-white btn-sm' href='" . $editRoute . "'>
                            <i class='bi-pencil-fill dropdown-item-icon'></i>
                           " . trans("Edit") . "
                        </a>";
                    }

                    if (adminAccessRoute(config('permissionList.Manage_Property.Properties.permission.delete'))) {
                        $delete = "<div class='btn-group'>
                      <button type='button' class='btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty' id='userEditDropdown' data-bs-toggle='dropdown' aria-expanded='false'></button>
                      <div class='dropdown-menu dropdown-menu-end mt-1' aria-labelledby='userEditDropdown'>
                        <a class='dropdown-item deleteProperty' href='javascript:void(0)'
                           data-route='" . $deleteRoute . "'
                           data-bs-toggle='modal' data-bs-target='#deletePropertyModal'>
                            <i class='fal fa-trash-alt dropdown-item-icon'></i>
                           " . trans("Delete") . "
                        </a>
                      </div>
                    </div>";
                    }
                } else {
                    $update = '-';
                }

                return "<div class='btn-group' role='group'>
                      $update
                      $delete
                  </div>";
            })
            ->rawColumns(['installment', 'status', 'action'])
            ->make(true);
    }

    public function propertyCreate()
    {
        $data['addresses'] = Address::where('status', 1)->get();
        $data['amenities'] = Amenity::where('status', 1)->get();
        $data['schedules'] = ManageTime::where('status', 1)->orderBy('time_type', 'ASC')->get();
        return view('admin.property.create', $data);
    }

    public function propertyStore(Request $request)
    {
        $is_invest_type = (int)$request->is_invest_type;
        $is_return_type = (int)$request->is_return_type;
        $isInstallment = (int)$request->is_installment ?? 0;

        $minimum_amount = ($is_invest_type == 1 ? null : $request->minimum_amount);
        $maximum_amount = ($is_invest_type == 1 ? null : $request->maximum_amount);
        $fixed_amount = ($is_invest_type == 1 ? $request->fixed_amount : null);
        $total_investment_amount = $request->total_investment_amount;

        $rules = [
            'title' => 'required|string|max:191',
            'address_id' => 'required|exists:addresses,id',
            'amenity_id' => 'nullable|exists:amenities,id',
            'location' => 'nullable|url',
            'video' => ['nullable', 'regex:/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/i'],
            'thumbnail' => 'required|mimes:jpg,jpeg,png|max:5120',
            'property_image.*' => 'nullable|mimes:jpeg,png,jpg',
            'installment_duration' => 'nullable|numeric|min:1',
            'fixed_amount' => 'nullable|numeric|min:1',
            'minimum_amount' => 'nullable|numeric|min:1',
            'maximum_amount' => 'nullable|gt:minimum_amount',
            'total_investment_amount' => 'required|nullable|numeric|min:1',
            'how_many_times' => 'nullable|numeric|min:1',
            'how_many_days' => 'nullable|exists:manage_times,id',
            'installment_amount' => 'nullable',
            'profit' => 'nullable',
            'loss' => 'nullable',
        ];

        $message = [
            'title.required' => __('Title is required'),
            'address_id.required' => __('Address is required'),
            'location.required' => __('Location is required'),
            'location.url' => __('Only embeded url are accepted'),
            'thumbnail.required' => __('Thumbnail field is required'),
            'property_image.*.mimes' => __('This property image must be a file of type: jpg, jpeg, png.'),
            'total_investment_amount.lt' => __('Fixed amount must be less then total invest amount'),
            'minimum_amount.lt' => __('Minimum amount must be less then total invest amount'),
            'maximum_amount.lt' => __('Maximum amount must be less then total invest amount'),
            'how_many_days.exists' => __('Invalid Profit Schedule'),
        ];

        if ($is_invest_type == 1 && $isInstallment == 1) {
            $rules['fixed_amount'] = ['required', 'numeric', 'min:1'];
            $rules['total_installments'] = ['required', 'numeric', 'min:1'];
            $rules['installment_amount'] = ['required'];
            $rules['installment_duration'] = ['required', 'numeric', 'min:1'];
        } elseif ($is_invest_type == 1) {
            $rules['fixed_amount'] = ['required', 'numeric', 'min:1'];
        } elseif ($is_invest_type == 0) {
            $rules['minimum_amount'] = ['required', 'numeric', 'min:1'];
            $rules['maximum_amount'] = ['required', 'gt:minimum_amount'];
        }

        if ($is_return_type == 0) {
            $rules['how_many_times'] = ['required', 'numeric', 'min:1'];
        }


        if ($fixed_amount != null && $total_investment_amount < $fixed_amount) {
            $rules['fixed_amount'] = ['lt:total_investment_amount'];
            $validate = Validator::make($request->all(), $rules, $message);
            return back()->withInput()->withErrors($validate);
        }

        if ($is_invest_type == 0 && $minimum_amount > $total_investment_amount && $maximum_amount > $total_investment_amount) {
            $rules['maximum_amount'] = ['lt:total_investment_amount'];
            $rules['minimum_amount'] = ['lt:total_investment_amount'];
            $validate = Validator::make($request->all(), $rules, $message);
            return back()->withInput()->withErrors($validate);
        } elseif ($is_invest_type == 1 && $minimum_amount > $total_investment_amount) {
            $rules['minimum_amount'] = ['lt:total_investment_amount'];
            $validate = Validator::make($request->all(), $rules, $message);
            return back()->withInput()->withErrors($validate);
        } elseif ($is_invest_type == 1 && $maximum_amount > $total_investment_amount) {
            $rules['maximum_amount'] = ['lt:total_investment_amount'];
            $validate = Validator::make($request->all(), $rules, $message);
            return back()->withInput()->withErrors($validate);
        }

        $this->validate($request, $rules, $message);

        $how_many_days = $request->how_many_days;
        $how_many_times = ($is_return_type == 0 ? $request->how_many_times : null);
        $is_installment = ($is_invest_type == 1 ? (int)$request->is_installment : 0);
        $total_installments = ($is_installment == 1 ? $request->total_installments : null);

        $requestInstallmentAmount = 0;

        if ($is_invest_type == 1 && $isInstallment == 1) {
            $requestInstallmentAmount += (int)$request->fixed_amount / (int)$total_installments;
        }

        $installment_amount = ($is_installment == 1 ? $requestInstallmentAmount : null);
        $installment_duration = ($is_installment == 1 ? $request->installment_duration : null);
        $installment_duration_type = ($is_installment == 1 ? $request->installment_duration_type : null);
        $installment_late_fee = ($is_installment == 1 ? $request->installment_late_fee : null);

        $profit = $request->profit;
        $profit_type = $request->profit_type;
        $is_capital_back = (int)$request->is_capital_back ?? 0;
        $is_investor = (int)$request->is_investor ?? 0;

        DB::beginTransaction();
        try {
            $manageTime = ManageTime::find($how_many_days);

            $property = new Property();
            $property->title = $request->title;
            $property->address_id = $request->address_id;
            $property->location = $request->location ?? null;
            $property->amenity_id = $request->amenity_id ?? null;
            $property->video = $request->video ?? null;
            $property->details = $request->details;

            $input_form = [];
            if ($request->has('faq_title')) {
                for ($a = 0; $a < count($request->faq_title); $a++) {
                    $arr = array();
                    $arr['field_name'] = $request->faq_title[$a];
                    $arr['field_value'] = $request->faq_details[$a];
                    $input_form[$arr['field_name']] = $arr;
                }
            }

            $property->faq = empty($input_form) ? null : $input_form;

            $property->is_invest_type = $is_invest_type;
            $property->is_installment = $is_installment;
            $property->is_return_type = $is_return_type;
            $property->is_investor = $is_investor;
            $property->is_capital_back = $is_capital_back;
            $property->is_featured = $request->is_featured ?? 0;
            $property->is_available_funding = $request->is_available_funding ?? 0;

            $property->minimum_amount = $minimum_amount;
            $property->maximum_amount = $maximum_amount;
            $property->fixed_amount = $fixed_amount;
            $property->total_investment_amount = $total_investment_amount;
            $property->available_funding = $total_investment_amount;
            $property->how_many_days = $how_many_days;
            $property->how_many_times = $how_many_times;
            $property->return_time = $manageTime->time ?? null;
            $property->return_time_type = $manageTime->time_type ?? null;
            $property->total_installments = $total_installments;
            $property->installment_amount = $installment_amount;
            $property->installment_duration = $installment_duration;
            $property->installment_duration_type = $installment_duration_type;
            $property->installment_late_fee = $installment_late_fee;
            $property->profit = $profit;
            $property->profit_type = $profit_type;
            $property->start_date = Carbon::parse($request->start_date);
            $property->expire_date = Carbon::parse($request->expire_date);
            $property->status = $request->status;


            if ($request->hasFile('thumbnail')) {
                $thumbnail = $this->fileUpload($request->thumbnail, config('filelocation.propertyThumbnail.path'), null, null, 'webp', 80, null, null);
                throw_if(!$thumbnail, __('Thumbnail could not be uploaded.'));
                if ($thumbnail) {
                    $property->thumbnail = $thumbnail['path'];
                    $property->driver = $thumbnail['driver'] ?? 'local';
                }
            }

            $saveProperty = $property->save();

            throw_if(!$saveProperty, 'Something went wrong while inserting property information!');

            if ($request->hasFile('property_image')) {
                foreach ($request->property_image as $key => $images) {
                    $image = new Image();
                    $imageData = $this->fileUpload($images, config('filelocation.property.path'), null, null, 'webp', null, null, null);
                    if (!$imageData) {
                        DB::rollBack();
                        throw new Exception(__('Image could not be uploaded.'));
                    }
                    if ($imageData) {
                        $image = new Image([
                            'image' => $imageData['path'],
                            'driver' => $imageData['driver'],
                        ]);
                    }

                    $propertyImages = $property->image()->save($image);

                    if (!$propertyImages) {
                        DB::rollBack();
                        throw new Exception(__('Something went wrong while inserting property images!'));
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Property has been created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function propertyDetails($id)
    {
        $data['property'] = Property::select('id', 'title', 'total_investment_amount', 'start_date', 'expire_date')->findOrFail($id);
        return view('admin.property.details', $data);
    }

    public function propertyEdit($id)
    {
        $data['addresses'] = Address::where('status', 1)->get();
        $data['amenities'] = Amenity::where('status', 1)->get();
        $data['schedules'] = ManageTime::where('status', 1)->orderBy('time_type', 'ASC')->get();
        $data['singleProperty'] = Property::with('image')->findOrFail($id);
        return view('admin.property.edit', $data);
    }

    public function propertyUpdate(Request $request, $id)
    {


        $is_invest_type = (int)$request->is_invest_type;
        $is_return_type = (int)$request->is_return_type;
        $isInstallment = (int)$request->is_installment ?? 0;

        $minimum_amount = ($is_invest_type == 1 ? null : $request->minimum_amount);
        $maximum_amount = ($is_invest_type == 1 ? null : $request->maximum_amount);
        $fixed_amount = ($is_invest_type == 1 ? $request->fixed_amount : null);
        $total_investment_amount = $request->total_investment_amount;

        $rules = [
            'title' => 'required|string|max:191',
            'address_id' => 'required|exists:addresses,id',
            'amenity_id' => 'nullable|exists:amenities,id',
            'location' => 'nullable|url',
            'video' => ['nullable', 'regex:/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/i'],
            'property_image.*' => 'nullable|mimes:jpeg,png,jpg',
            'installment_duration' => 'nullable|numeric|min:1',
            'fixed_amount' => 'nullable|numeric|min:1',
            'minimum_amount' => 'nullable|numeric|min:1',
            'maximum_amount' => 'nullable|gt:minimum_amount',
            'total_investment_amount' => 'required|nullable|numeric|min:1',
            'how_many_times' => 'nullable|numeric|min:1',
            'how_many_days' => 'nullable|exists:manage_times,id',
            'installment_amount' => 'nullable',
            'profit' => 'nullable',
            'loss' => 'nullable',
        ];

        $message = [
            'title.*.required' => __('Title is required'),
            'address_id.required' => __('Address is required'),
            'location.required' => __('Location is required'),
            'location.url' => __('Only embeded url are accepted'),
            'property_image.*.mimes' => __('This property image must be a file of type: jpg, jpeg, png.'),
            'total_investment_amount.lt' => __('Fixed amount must be less then total invest amount'),
            'minimum_amount.lt' => __('Minimum amount must be less then total invest amount'),
            'maximum_amount.lt' => __('Maximum amount must be less then total invest amount'),
            'how_many_days.exists' => __('Invalid Profit Schedule'),
        ];

        if ($is_invest_type == 1 && $isInstallment == 1) {
            $rules['fixed_amount'] = ['required', 'numeric', 'min:1'];
            $rules['total_installments'] = ['required', 'numeric', 'min:1'];
            $rules['installment_amount'] = ['required'];
            $rules['installment_duration'] = ['required', 'numeric', 'min:1'];
        } elseif ($is_invest_type == 1) {
            $rules['fixed_amount'] = ['required', 'numeric', 'min:1'];
        } elseif ($is_invest_type == 0) {
            $rules['minimum_amount'] = ['required', 'numeric', 'min:1'];
            $rules['maximum_amount'] = ['required', 'gt:minimum_amount'];
        }

        if ($is_return_type == 0) {
            $rules['how_many_times'] = ['required', 'numeric', 'min:1'];
        }


        if ($fixed_amount != null && $total_investment_amount < $fixed_amount) {
            $rules['fixed_amount'] = ['lt:total_investment_amount'];
            $validate = Validator::make($request->all(), $rules, $message);
            return back()->withInput()->withErrors($validate);
        }

        if ($is_invest_type == 0 && $minimum_amount > $total_investment_amount && $maximum_amount > $total_investment_amount) {
            $rules['maximum_amount'] = ['lt:total_investment_amount'];
            $rules['minimum_amount'] = ['lt:total_investment_amount'];
            $validate = Validator::make($request->all(), $rules, $message);
            return back()->withInput()->withErrors($validate);
        } elseif ($is_invest_type == 1 && $minimum_amount > $total_investment_amount) {
            $rules['minimum_amount'] = ['lt:total_investment_amount'];
            $validate = Validator::make($request->all(), $rules, $message);
            return back()->withInput()->withErrors($validate);
        } elseif ($is_invest_type == 1 && $maximum_amount > $total_investment_amount) {
            $rules['maximum_amount'] = ['lt:total_investment_amount'];
            $validate = Validator::make($request->all(), $rules, $message);
            return back()->withInput()->withErrors($validate);
        }

        $this->validate($request, $rules, $message);

        $how_many_days = $request->how_many_days;
        $how_many_times = ($is_return_type == 0 ? $request->how_many_times : null);
        $is_installment = ($is_invest_type == 1 ? (int)$request->is_installment : 0);
        $total_installments = ($is_installment == 1 ? $request->total_installments : null);

        $requestInstallmentAmount = 0;

        if ($is_invest_type == 1 && $isInstallment == 1) {
            $requestInstallmentAmount += (int)$request->fixed_amount / (int)$total_installments;
        }

        $installment_amount = ($is_installment == 1 ? $requestInstallmentAmount : null);
        $installment_duration = ($is_installment == 1 ? $request->installment_duration : null);
        $installment_duration_type = ($is_installment == 1 ? $request->installment_duration_type : null);
        $installment_late_fee = ($is_installment == 1 ? $request->installment_late_fee : null);

        $profit = $request->profit;
        $profit_type = $request->profit_type;
        $is_capital_back = (int)$request->is_capital_back ?? 0;
        $is_investor = (int)$request->is_investor ?? 0;

        DB::beginTransaction();
        try {
            $manageTime = ManageTime::find($how_many_days);

            $property = Property::findOrFail($id);
            $property->title = $request->title;
            $property->address_id = $request->address_id;
            $property->location = $request->location ?? null;
            $property->amenity_id = $request->amenity_id ?? null;
            $property->video = $request->video ?? null;
            $property->details = $request->details;

            $input_form = [];
            if ($request->has('faq_title')) {
                for ($a = 0; $a < count($request->faq_title); $a++) {
                    $arr = array();
                    $arr['field_name'] = $request->faq_title[$a];
                    $arr['field_value'] = $request->faq_details[$a];
                    $input_form[$arr['field_name']] = $arr;
                }
            }

            $property->faq = empty($input_form) ? null : $input_form;

            $property->is_invest_type = $is_invest_type;
            $property->is_installment = $is_installment;
            $property->is_return_type = $is_return_type;
            $property->is_investor = $is_investor;
            $property->is_capital_back = $is_capital_back;
            $property->is_featured = $request->is_featured ?? 0;
            $property->is_available_funding = $request->is_available_funding ?? 0;
            $property->minimum_amount = $minimum_amount;
            $property->maximum_amount = $maximum_amount;
            $property->fixed_amount = $fixed_amount;
            $property->total_investment_amount = $total_investment_amount;
            $property->available_funding = $total_investment_amount;
            $property->how_many_days = $how_many_days;
            $property->how_many_times = $how_many_times;

            $property->return_time = $manageTime->time ?? null;
            $property->return_time_type = $manageTime->time_type ?? null;

            $property->total_installments = $total_installments;
            $property->installment_amount = $installment_amount;
            $property->installment_duration = $installment_duration;
            $property->installment_duration_type = $installment_duration_type;
            $property->installment_late_fee = $installment_late_fee;
            $property->profit = $profit;
            $property->profit_type = $profit_type;
            $property->start_date = Carbon::parse($request->start_date);
            $property->expire_date = Carbon::parse($request->expire_date);
            $property->status = $request->status;

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $this->fileUpload($request->thumbnail, config('filelocation.propertyThumbnail.path'), null, null, 'webp', null, null, null);
                throw_if(!$thumbnail, __('Thumbnail could not be uploaded.'));
                if ($thumbnail) {
                    $property->thumbnail = $thumbnail['path'];
                    $property->driver = $thumbnail['driver'] ?? 'local';
                }
            }

            // Snapshot revision trước khi lưu
            PropertyRevision::create([
                'property_id'    => $property->id,
                'editor_id'      => Auth::guard('admin')->id(),
                'change_summary' => 'Updated via admin panel',
                'payload'        => $property->only([
                    'title', 'details', 'thumbnail', 'amenity_id', 'location', 'video',
                    'total_investment_amount', 'profit', 'start_date', 'expire_date', 'status',
                ]),
            ]);

            $saveProperty = $property->save();

            throw_if(!$saveProperty, 'Something went wrong while inserting property information!');

            if ($request->hasFile('property_image')) {
                foreach ($request->property_image as $key => $images) {
                    $image = new Image();
                    $imageData = $this->fileUpload($images, config('filelocation.property.path'), null, null, 'webp', null, null, null);
                    if (!$imageData) {
                        DB::rollBack();
                        throw new Exception(__('Image could not be uploaded.'));
                    }
                    if ($imageData) {
                        $image = new Image([
                            'image' => $imageData['path'],
                            'driver' => $imageData['driver'],
                        ]);
                    }

                    $propertyImages = $property->image()->save($image);

                    if (!$propertyImages) {
                        DB::rollBack();
                        throw new Exception(__('Something went wrong while inserting property images!'));
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Property Updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function propertyDelete($id)
    {
        $property = Property::with('image')->findOrFail($id);

        foreach ($property->image as $image) {
            $this->fileDelete($image->driver, $image->image);
            $image->delete();
        }

        $this->fileDelete($property->driver, $property->thumbnail);
        $property->delete();

        return back()->with('success', 'Property Deleted Successfully!');
    }

    public function shareInvestment()
    {
        return view('admin.property.shareInvestment');
    }

    public function propertyWishlist()
    {
        return view('admin.property.wishList');
    }

    public function propertyWishlistSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];
        $filterProperty = $request->filterProperty;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $wishLists = Favourite::with(['get_user', 'get_property'])
            ->when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
                return $query->whereHas('get_property', function ($query) use ($search) {
                    $query->where('title', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($filterProperty), function ($query) use ($filterProperty) {
                $query->whereHas('get_property', function ($q) use ($filterProperty) {
                    $q->where('title', 'LIKE', "%{$filterProperty}%");
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->orderBy('id', 'desc')
            ->get();

        return DataTables::of($wishLists)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('property', function ($item) {
                return optional($item->get_property)->title;
            })
            ->addColumn('user', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->get_user)->firstname . ' ' . optional($item->get_user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->get_user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $deleteRoute = route('admin.property.wishlist.delete', $item->id);

                if (adminAccessRoute(config('permissionList.Manage_Property.Wishlist.permission.delete'))) {
                    $delete = "<a class='btn btn-white btn-sm deleteWishlistProperty' href='javascript:void(0)'
                           data-route='" . $deleteRoute . "'
                           data-bs-toggle='modal' data-bs-target='#deleteWishlistPropertyModal'>
                            <i class='fal fa-trash dropdown-item-icon'></i>
                           " . trans("Delete") . "
                        </a>";
                } else {
                    $delete = '-';
                }

                return "<div class='btn-group' role='group'>
                      $delete
                  </div>";
            })
            ->rawColumns(['user', 'action'])
            ->make(true);
    }

    public function propertyWishlistDelete($id)
    {
        $favourite = Favourite::findOrFail($id);
        $favourite->delete();
        return back()->with('success', 'Wishlist deleted successfully!');
    }

    /**
     * POST /admin/property/{id}/seo — Tạo hoặc cập nhật SEO meta
     */
    public function seoStore(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $this->validate($request, [
            'meta_title'       => 'nullable|string|max:191',
            'meta_description' => 'nullable|string|max:500',
            'slug'             => 'nullable|string|max:191|unique:property_seo,slug,' . optional($property->seo)->id,
            'og_image_id'      => 'nullable|exists:images,id',
        ]);

        PropertySeo::updateOrCreate(
            ['property_id' => $property->id],
            [
                'meta_title'       => $request->meta_title,
                'meta_description' => $request->meta_description,
                'slug'             => $request->slug ? Str::slug($request->slug) : null,
                'og_image_id'      => $request->og_image_id,
            ]
        );

        return back()->with('success', __('SEO settings saved.'));
    }

    /**
     * GET /admin/property/{id}/revisions — JSON danh sách lịch sử (20 bản gần nhất)
     */
    public function revisions(Request $request, $id)
    {
        abort_if(!$request->ajax(), 404);

        $revisions = PropertyRevision::where('property_id', $id)
            ->with('editor:id,name')
            ->latest('created_at')
            ->limit(20)
            ->get()
            ->map(fn($r) => [
                'id'             => $r->id,
                'editor_name'    => optional($r->editor)->name,
                'change_summary' => $r->change_summary,
                'created_at'     => $r->created_at?->format('d/m/Y H:i'),
            ]);

        return response()->json($revisions);
    }

    /**
     * POST /admin/property/rollback/{revId} — Khôi phục property từ snapshot
     */
    public function rollback(Request $request, $revId)
    {
        abort_if(!$request->ajax(), 404);

        $revision = PropertyRevision::findOrFail($revId);
        $property = Property::findOrFail($revision->property_id);

        // Restore các trường đã snapshot
        $property->fill($revision->payload);

        // Snapshot hiện tại trước khi rollback
        PropertyRevision::create([
            'property_id'    => $property->id,
            'editor_id'      => Auth::guard('admin')->id(),
            'change_summary' => 'Rollback to revision #' . $revision->id,
            'payload'        => $property->only([
                'title', 'details', 'thumbnail', 'amenity_id', 'location', 'video',
                'total_investment_amount', 'profit', 'start_date', 'expire_date', 'status',
            ]),
        ]);

        $property->save();

        return response()->json(['success' => true, 'message' => __('Rollback successful.')]);
    }
}
