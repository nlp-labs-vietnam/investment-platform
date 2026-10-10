<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\Rank;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RankController extends Controller
{
    use Upload;

    public function ranks()
    {
        return view('admin.rank.ranks');
    }

    public function rankSearch(Request $request)
    {
        abort_if(!$request->ajax(), 404);
        $search = $request->search['value'];

        $filterRank = $request->filterRank;
        $filterStatus = $request->filterStatus;

        $ranks = Rank::when(isset($request->search['value']) && $request->search['value'] != "", function ($query) use ($search) {
            return $query->where('rank_name', 'LIKE', "%{$search}%")
                ->orWhere('rank_level', 'LIKE', "%{$search}%");
        })
            ->when(!empty($filterRank), function ($query) use ($filterRank) {
                $query->where('rank_name', 'LIKE', "%{$filterRank}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->orderBy('id', 'ASC');


        return DataTables::of($ranks)
            ->addColumn('sl', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('rank', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  ' . $item->rankIcon() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $item->rank_name . '</h5>
                                  <span class="fs-6 text-body">' . $item->rank_level . '</span>
                                </div>
                              </a>';

            })
            ->addColumn('min_invest', function ($item) {
                return currencyPosition($item->min_invest);
            })
            ->addColumn('min_deposit', function ($item) {
                return currencyPosition($item->min_deposit);
            })
            ->addColumn('min_earning', function ($item) {
                return currencyPosition($item->min_earning);
            })
            ->addColumn('bonus', function ($item) {
                return currencyPosition($item->bonus);
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
                $editRoute = route('admin.rank.edit', $item->id);
                $deleteRoute = route('admin.rank.delete', $item->id);

                $edit = '';
                $delete = '';

                if (adminAccessRoute(array_merge(config('permissionList.Manage_Rank.All_Ranks.permission.edit'), config('permissionList.Manage_Rank.All_Ranks.permission.delete')))) {
                    if (adminAccessRoute(config('permissionList.Manage_Rank.All_Ranks.permission.edit'))) {
                        $edit = "<a class='btn btn-white btn-sm' href='" . $editRoute . "'>
                            <i class='bi-pencil-fill dropdown-item-icon'></i>
                           " . trans("Edit") . "
                        </a>";
                    }

                    if (adminAccessRoute(config('permissionList.Manage_Rank.All_Ranks.permission.delete'))) {
                        $delete = "<div class='btn-group'>
                      <button type='button' class='btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty' id='userEditDropdown' data-bs-toggle='dropdown' aria-expanded='false'></button>
                      <div class='dropdown-menu dropdown-menu-end mt-1' aria-labelledby='userEditDropdown'>
                        <a class='dropdown-item deleteRank' href='javascript:void(0)'
                           data-route='" . $deleteRoute . "'
                           data-bs-toggle='modal' data-bs-target='#deleteRankModal'>
                            <i class='fal fa-trash-alt dropdown-item-icon'></i>
                           " . trans("Delete") . "
                        </a>
                      </div>
                    </div>";
                    }
                } else {
                    $edit = '-';
                }

                return "<div class='btn-group' role='group'>
                      $edit
                      $delete
                  </div>";
            })
            ->rawColumns(['rank', 'status', 'action'])
            ->make(true);
    }

    public function rankCreate()
    {
        return view('admin.rank.create');
    }

    public function rankStore(Request $request)
    {
        $rules = [
            'rank_name' => 'required|string|max:191',
            'rank_level' => 'nullable|string|max:191',
            'min_invest' => 'required',
            'min_deposit' => 'required',
            'min_earning' => 'required',
            'bonus' => 'required',
            'rank_icon' => 'nullable',
        ];

        $message = [
            'rank_name.required' => __('Rank Name is required'),
            'min_invest.required' => __('Minimum Invest field is required'),
            'min_deposit.required' => __('Minimum Deposit field is required'),
            'min_earning.required' => __('Minimum Earning field is required'),
            'bonus.required' => __('Bonus field is required'),
        ];

        $this->validate($request, $rules, $message);

        DB::beginTransaction();

        try {
            $rank = new Rank();
            $rank->rank_name = $request->rank_name;
            $rank->rank_level = $request->rank_level;
            $rank->min_invest = $request->min_invest;
            $rank->min_deposit = $request->min_deposit;
            $rank->min_earning = $request->min_earning;
            $rank->bonus = $request->bonus;
            $rank->description = $request->description;
            $rank->status = $request->status;

            if ($request->hasFile('rank_icon')) {
                $rankIcon = $this->fileUpload($request->rank_icon, config('filelocation.rank.path'), null, null, 'webp', null, null, null);
                throw_if(!$rankIcon, __('Rank icon could not be uploaded.'));
                if ($rankIcon) {
                    $rank->rank_icon = $rankIcon['path'];
                    $rank->driver = $rankIcon['driver'] ?? 'local';
                }
            }

            $rank->save();
            DB::commit();
            return back()->with('success', 'rank created successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->with('error', $exception->getMessage());
        }
    }

    public function rankEdit($id)
    {
        $data['singleRank'] = Rank::findOrFail($id);
        return view('admin.rank.edit', $data);
    }

    public function rankUpdate(Request $request, $id)
    {
        $rules = [
            'rank_name' => 'required|string|max:191',
            'rank_level' => 'nullable|string|max:191',
            'min_invest' => 'required',
            'min_deposit' => 'required',
            'min_earning' => 'required',
            'bonus' => 'required',
            'rank_icon' => 'nullable',
        ];

        $message = [
            'rank_name.required' => __('Rank Name is required'),
            'min_invest.required' => __('Minimum Invest field is required'),
            'min_deposit.required' => __('Minimum Deposit field is required'),
            'min_earning.required' => __('Minimum Earning field is required'),
            'bonus.required' => __('Bonus field is required'),
        ];

        $this->validate($request, $rules, $message);

        DB::beginTransaction();

        try {
            $rank = Rank::findOrFail($id);
            $rank->rank_name = $request->rank_name;
            $rank->rank_level = $request->rank_level;
            $rank->min_invest = $request->min_invest;
            $rank->min_deposit = $request->min_deposit;
            $rank->min_earning = $request->min_earning;
            $rank->bonus = $request->bonus;
            $rank->description = $request->description;
            $rank->status = $request->status;

            if ($request->hasFile('rank_icon')) {
                $rankIcon = $this->fileUpload($request->rank_icon, config('filelocation.rank.path'), null, null, 'webp', null, null, null);
                throw_if(!$rankIcon, __('Rank icon could not be uploaded.'));
                if ($rankIcon) {
                    $rank->rank_icon = $rankIcon['path'];
                    $rank->driver = $rankIcon['driver'] ?? 'local';
                }
            }

            $rank->save();
            DB::commit();
            return back()->with('success', 'rank updated successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->with('error', $exception->getMessage());
        }
    }

    public function rankBonus()
    {
        return view('admin.rank.rankBonus');
    }

    public function rankBonusUpdate(Request $request)
    {
        $request->validate([
            'is_rank_bonus' => 'nullable|numeric|in:0,1',
        ]);

        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'is_rank_bonus' => $request->is_rank_bonus,
            ]);

            if (!$response)
                throw new Exception('Something went wrong, when updating the data.');

            session()->flash('success', 'Rank bonus updated successfully');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rankDelete($id)
    {
        $rank = Rank::findOrFail($id);
        $this->fileDelete($rank->driver, $rank->rank_icon);
        $rank->delete();
        return back()->with('success', 'Rank deleted successfully!');
    }

    public function sortRanks(Request $request)
    {
        $data = $request->all();
        foreach ($data['sort'] as $key => $value) {

            Rank::where('id', $value)->update([
                'sort_by' => $key + 1
            ]);
        }

    }

}
