
@if(config('demo.IS_DEMO'))
    <div class="dashboard_announcement_bar"
         style="background-image: url({{ asset('assets/admin/img/announcement_bar.png') }})">
        <div class="container">
            <div class="wrapper py-2">
                <div class="announcement_bar-notice d-flex flex-wrap">
                    <div class="txt">
                        @lang("This is a demo website - Buy " .basicControl()->site_title.  " using our official link!")
                    </div>
                    <a href="{{ config('requirements.item_url') }}"
                       class="btn btn-sm mx-2 purchase-item-btn" target="_blank">@lang("Buy Now")</a>
                </div>
                <div class="announcement-close">
                    <button class="btn btn-sm" type="button"><i class="bi bi-x-square text-white"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.announcement-close button').addEventListener('click', function() {
            const announcementBar = document.querySelector('.dashboard_announcement_bar');
            announcementBar.classList.remove('dashboard_announcement_bar');
            announcementBar.style.backgroundImage = 'none';

            document.body.classList.remove('demo');
        });
    </script>
@endif
