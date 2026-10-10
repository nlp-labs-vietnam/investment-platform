<nav aria-label="Page navigation example"
     v-if="pagination.per_page < pagination.total">
    <ul class="pagination justify-content-center ">
        <li class="page-item" :class="{ disabled: pagination.current_page == 1 }"><a
                class="page-link" @click.prevent="updateItems('back')" v-cloak>«</a></li>
        <li class="page-item" v-for="(link, index) in links"
            :class="{ active: link.label == pagination.current_page }"
            v-if="(pagination.current_page < 7 && index < 8) || (pagination.current_page > 6  && index <  2)">
            <a class="page-link" href="#" @click.prevent="updateItems(link)" v-cloak >@{{link.label}}</a>
        </li>
        <li class="page-item disabled"
            :class="{ active: link == pagination.current_page }" aria-disabled="true" v-if="links.length > 10">
            <span class="page-link">@lang('...')</span>
        </li>
        <li class="page-item" v-for="link in links"
            :class="{ active: link == pagination.current_page }"
            v-if="(pagination.current_page > 6 && pagination.current_page <= links.length - 6) && (link >= pagination.current_page - 3 && link <= pagination.current_page + 3)">
            <a class="page-link" href="#" @click.prevent="updateItems(link)" v-cloak>@{{link}}</a>
        </li>
        <li class="page-item disabled"
            :class="{ active: link == pagination.current_page }" aria-disabled="true"
            v-if="links.length > 10 && pagination.current_page > 6 && pagination.current_page <= links.length - 6">
            <span class="page-link">@lang('...')</span>
        </li>
        <li class="page-item" v-for="(link, index) in links"
            :class="{ active: link == pagination.current_page }"
            v-if="(pagination.current_page < 7 && pagination.current_page <= links.length - 6 && links.length <= 6 && index > (links.length - 3)) || (pagination.current_page < 7  && links.length > 6 && index > (links.length - 3)) || (pagination.current_page >= 7 && pagination.current_page > links.length - 6 && index > (links.length - 9)) || (pagination.current_page > 6 && pagination.current_page <= links.length - 6 && index >  links.length - 3)">
            <a class="page-link" href="#" @click.prevent="updateItems(link)" v-cloak>@{{link}}</a>
        </li>
        <li class="page-item"
            :class="{ disabled: pagination.current_page == pagination.last_page }"><a
                class="page-link" @click.prevent="updateItems('next')" v-cloak>»</a></li>
    </ul>
</nav>
