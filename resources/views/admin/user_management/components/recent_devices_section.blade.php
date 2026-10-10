<div id="recentDevicesSection" class="card">
    <div class="card-header">
        <h2 class="card-title h4">@lang('Recent devices')</h2>
    </div>
    <div class="card-body text-center">
        @if(count($userLoginInfo) == 0)
            <div class="card-body ">
                <img class="avatar avatar-xxl mb-3" src="{{asset('assets/admin/img/oc-error.svg')}}"
                     alt="Image Description" data-hs-theme-appearance="default">
                <img class="avatar avatar-xxl mb-3" src="{{asset('assets/admin/img/oc-error-light.svg')}}"
                     alt="Image Description" data-hs-theme-appearance="dark">
                <p class="card-text">@lang("No data to show")</p>
            </div>
        @else
            <p class="card-text text-start">@lang("View and manage devices where you're currently logged in.")</p>
        @endif
    </div>

    @if(0 < count($userLoginInfo))
        <div class="table-responsive">
            <table class="table table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                <tr>
                    <th>@lang('Browser')</th>
                    <th>@lang('Device')</th>
                    <th>@lang('Location')</th>
                    <th>@lang('Most recent activity')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($userLoginInfo as $key =>  $userLogin)
                    <tr>
                        <td class="align-items-center">
                            <img class="avatar avatar-xss me-2"
                                 src="{{ asset("assets/admin/img/browser/".browserIcon($userLogin->browser).".svg") }}"
                                 alt="Image Description"> {{ $userLogin->browser }} on {{ $userLogin->os }}
                        </td>
                        <td>
                            <i class="{{deviceIcon($userLogin->get_device)}} fs-3 me-2"></i> {{ $userLogin->get_device }}
                            @if($key== 0 )
                                <span
                                    class="badge bg-soft-success text-success ms-1">@lang("Current")</span>
                            @endif</td>
                        <td>{{ $userLogin->country }}</td>
                        <td>{{ timeAgo($userLogin->created_at) }}</td>
                    </tr>
                @empty

                @endforelse
                </tbody>
            </table>
        </div>
    @endif

</div>


