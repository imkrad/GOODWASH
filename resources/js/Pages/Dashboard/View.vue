<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="View Booking" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div v-if="selected">
            <div class="row mb-3">
                <div class="col-md">
                    <div class="row align-items-center g-3">
                        <div class="col-md">
                            <div >
                                <h5 class="fs-15 fw-semibold text-primary mb-2">{{selected.name}}</h5>
                                <div class="hstack gap-3 flex-wrap">
                                    <div class="text-muted"><i class="ri-map-pin-fill me-1"></i>{{selected.address}}</div>
                                    <div class="vr"></div>
                                    <div class="text-muted"><i class="ri-calendar-2-fill me-1"></i>{{selected.created_at}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="text-muted"/>
            <div class="card-body text-center">
                <div class="row g-3">
                    <div class="col-6">
                        <p class="text-muted mb-2 fs-11 text-uppercase fw-semibold">Email</p>
                        <h5 class="fs-12 mb-0"><span id="invoice-no">{{selected.email}}</span></h5>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-2 fs-11 text-uppercase fw-semibold">Contact no.</p>
                        <h5 class="fs-12 mb-0"><span id="invoice-no">{{selected.contact_no}}</span></h5>
                    </div>
                </div>
            </div>
            <hr class="text-muted"/>
            <table class="table table-nowrap table-bordered align-middle mb-0">
                <thead>
                    <tr class="table-light fs-11">
                        <th style="width: 50%;" class="text-center">Type</th>
                        <th style="width: 50%;" class="text-center">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fs-12">
                        <td class="text-center">{{selected.type}}</td>
                        <td class="text-center">{{selected.reserved_at}}</td>
                    </tr>
                </tbody>
            </table>
            <hr class="text-muted"/>

        </div>
        <template v-slot:footer>
            <b-button v-if="selected.status.id == 1" @click="submit(4)" variant="danger" block>Cancel</b-button>
            <b-button v-if="selected.status.id == 1" @click="submit(2)" variant="info" :disabled="form.processing" block>Mark as Confirmed</b-button>
            <b-button v-if="selected.status.id == 2" @click="submit(3)" variant="success" :disabled="form.processing" block>Mark as Completed</b-button>
            <b-button v-if="selected.status.id == 3 || selected.status.id == 4" @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    props: ['statuses'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                status_id: null,
            }),
            selected: {
                status: {}
            },
            showModal: false,
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.form.id = this.selected.id,
            this.showModal = true;
        },
        submit(status){
            this.form.status_id = status;
            this.form.put('/customers/update', {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('update',this.$page.props.flash.data.data);
                    this.hide();
                },
            });
        },
        hide(){
            this.user = {};
            this.form.reset();
            this.form.clearErrors();
            this.showModal = false;
        }
    }
}
</script>