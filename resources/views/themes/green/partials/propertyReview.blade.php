@if($property)
    @php
        $avgRating = $property->avg_rating;
        $fullStars = floor($avgRating);
        $hasHalfStar = $avgRating - $fullStars > 0;
        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
    @endphp
    <li>
        {{-- Full stars --}}
        @for($i = 0; $i < $fullStars; $i++)
            <i class="active fa-solid fa-star"></i>
        @endfor

        {{-- Half star --}}
        @if($hasHalfStar)
            <i class="active fa-solid fa-star-half"></i>
        @endif

        {{-- Empty stars --}}
        @for($i = 0; $i < $emptyStars; $i++)
            <i class="fa-solid fa-star"></i>
        @endfor
    </li>

    <span>
        ({{ $property->reviews_count <= 1 ? $property->reviews_count . trans(' review') : $property->reviews_count . trans(' reviews') }})
    </span>
@endif
