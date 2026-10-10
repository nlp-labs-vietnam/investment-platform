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
        <i class="fas fa-star" aria-hidden="true"></i>
        @php
            $j = $j + 1;
        @endphp
    @endfor

    @if($average_review != intval($average_review))
        <i class="fas fa-star-half-alt"></i>
        @php
            $j = $j + 1;
        @endphp
    @endif

    @if($average_review == 0 || $average_review != null)
        @for($j; $j < 5; $j++)
            <i class="far fa-star"></i>
        @endfor
    @endif
</span>
