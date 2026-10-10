@foreach ($menuArray as $key => $value)
    <div class="list-group-item mb-2" draggable="false" style="">
        <div class="d-flex justify-content-between drop-content">
            <span>{{ getPageName(is_numeric($key) ? $value : $key) }}</span>
            <i class="fa-light fa-xmark text-danger remove-icon"></i>
        </div>
        <input type="hidden" value="{{ is_numeric($key) ? $value : $key }}" name="menu_item[{{ is_numeric($key) ? $value : $key }}]">
        <div class="js-sortable list-group nested-list" data-hs-sortable-options='{
                                                 "animation": 150,
                                                 "group": "MenuSorting"
                                            }'>
            @if (is_array($value))
                @include('admin.frontend_management.components.header_menu', ['menuArray' => $value])
            @endif
        </div>
    </div>
@endforeach
