<span>
    @php
        $isCheck = 0;
        $j = 0;
    @endphp

    @if($average_review != intval($average_review))
        @php
            $isCheck = 1;
        @endphp
    @endif

    @for($i = $average_review; $i > $isCheck; $i--)
        <i class="fa-solid fa-star" aria-hidden="true"></i>
        @php
            $j = $j + 1;
        @endphp
    @endfor

    @if($average_review != intval($average_review))
        <i class="fa-solid fa-star-half-stroke"></i>
        @php
            $j = $j + 1;
        @endphp
    @endif

    @if($average_review == 0 || $average_review != null)
        @for($j; $j < 5; $j++)
            <i class="fa-regular fa-star"></i>
        @endfor
    @endif
</span>
