<template>
    <b-row class="">
        <b-col lg="4" md="6" v-for="(item, index) of events" :key="index">
            <b-card no-body>
                <b-card-body>
                    <div @click="view(item.id)" class="d-flex align-items-center" style="cursor: pointer;">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary rounded-circle fs-3">
                                <i :class="`ri-bill-line align-middle`"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                {{ item.name }}
                            </p>
                            <h4 class="mb-0">
                                <span class="counter-value">
                                {{ item.participants_count }} / {{item.maximum}}
                                </span>
                            </h4>
                        </div>
                    </div>
                </b-card-body>
            </b-card>
        </b-col>
        <b-col lg="12">
            <b-card style="height: calc(100vh - 190px); overflow: auto;">
                 <b-row class="g-2 mb-2 mt-n2">
                    <b-col lg>
                        <div class="input-group mb-1">
                            <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                            <input type="text" v-model="filter.keyword" placeholder="Search User" class="form-control" style="width: 35%;">
                            <!-- <span  class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                                <i class="bx bx-refresh search-icon"></i>
                            </span> -->
                            <b-button @click="refresh()" type="button" variant="primary" >
                                <i class="bx bx-refresh search-icon"></i>
                            </b-button>
                        </div>
                    </b-col>
                </b-row>
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <ul class="nav nav-tabs nav-tabs-custom nav-success border border-dashed border-end-0 border-start-0 fs-12" role="tablist">
                            <li class="nav-item">
                                <BLink @click="viewStatus(10,null)" class="nav-link py-3 active text-primary" data-bs-toggle="tab" role="tab" aria-selected="true">
                                <i class="ri-apps-2-line me-1 align-bottom"></i> All Requests
                                </BLink>
                            </li>
                            <li class="nav-item" v-for="(list,index) in statuses" v-bind:key="index">
                                <BLink @click="viewStatus(index,list.value)" class="nav-link py-3" :class="(this.index == index) ? list.others+' active' : ''" data-bs-toggle="tab" role="tab" aria-selected="false">
                                    <i :class="icons[index]" class="me-1 align-bottom"></i>
                                    {{ list.name }} <BBadge v-if="counts[index] > 0" :class="list.color" class="align-middle ms-1">{{counts[index]}}</BBadge>
                                </BLink>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown mt-3">
                            <a class="text-reset dropdown-btn" href="#" target="_self" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-uppercase fw-semibold fs-11">Sort by : </span>
                                <span class="text-muted">{{filter.sortby}} ({{filter.sort}})<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <a class="dropdown-item" href="#" @click="viewSort('Reserved At','ASC')" target="_self">Reserved Date</a>
                                <a class="dropdown-item" href="#" @click="viewSort('Created At','ASC')" target="_self">Created At (asc)</a>
                                <a class="dropdown-item" href="#" @click="viewSort('Created At','DESC')" target="_self">Created At (desc)</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th></th>
                                <th style="width: 20%;">Name</th>
                                <th style="width: 15%;" class="text-center">Address</th>
                                <th style="width: 10%;" class="text-center">Email</th>
                                <th style="width: 10%;" class="text-center">Contact no.</th>
                                <th style="width: 10%;" class="text-center">Type</th>
                                <th style="width: 10%;" class="text-center">Reserved At</th>
                                <th style="width: 10%;" class="text-center">Created At</th>
                                <th style="width: 10%;" class="text-center">Status</th>
                                <th style="width: 7%;" ></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list,index) in lists" v-bind:key="index">
                                <td class="text-center"> 
                                    {{ index + 1 }}.
                                </td>
                                <td>
                                    <h5 class="fs-13 mb-0 text-dark">{{list.name}}</h5>
                                </td>
                                <td class="text-center fs-12">{{list.address}}</td>
                                <td class="text-center fs-12">{{list.email}}</td>
                                <td class="text-center fs-12">{{list.contact_no}}</td>
                                <td class="text-center fs-12">{{list.type}}</td>
                                <td class="text-center fs-12">{{list.reserved_at}}</td>
                                <td class="text-center fs-12">{{list.created_at}}</td>
                                <td class="text-center">
                                    <span :class="'badge '+list.status.color">{{list.status.name}}</span>
                                </td>
                                <td class="text-end">
                                    <b-button @click="openView(list,index)" variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="me-1">
                                        <i class="ri-eye-fill align-bottom"></i>
                                    </b-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                </div>
            </b-card>
        </b-col>
    </b-row>
    <View @update="updateItem" :statuses="statuses" ref="view"/>
</template>
<script>
import _ from 'lodash';
import View from './View.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, View }, 
    props: ['statuses','counts'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            filter: {
                keyword: null,
                status: null,
                sortby: 'Created At',
                sort: 'desc'
            },
            index: null,
            icons: ['ri-information-line','ri-indeterminate-circle-line','ri-checkbox-circle-line','ri-close-circle-line'],
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/customers';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    status: this.filter.status,
                    sortby: this.filter.sortby,
                    sort: this.filter.sort,
                    count: ((window.innerHeight-420)/52)
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
        openView(data,index){
            this.index = index;
            this.$refs.view.show(data);
        },  
        viewStatus(index,status){
            this.index = index;
            this.filter.status = status;
            this.fetch();
        },
        viewSort(sortby,sort){
            this.filter.sortby = sortby;
            this.filter.sort = sort;
            this.fetch();
        },
        updateItem(data){
            this.lists[this.index] = data;
        },
    }
}
</script>