<template>
    <div>
        <Header />
        <div class="app-main flex-column flex-row-fluid iris-app-main">
            <div class="d-flex flex-column flex-column-fluid">
                <div class="app-content flex-column-fluid">
                    <div class="app-container mx-auto" style="width: 90%">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-md-row-fluid ms-lg-12">
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-header border-0">
                                        <div class="card-title d-flex justify-content-between w-full">
                                            <h3 class="fw-bolder m-0">Interview Calendar</h3>
                                            <div class="d-flex align-items-center">
                                                <router-link class="btn btn-primary" :to="{ name: 'client.interview.create' }">Add Interview</router-link>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="collapse show">
                                        <div class="card-body border-top p-9">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <FullCalendar :options="calendarOptions">
                                                        <template v-slot:eventContent='arg'>
                                                            <div class="pl-2">
                                                                <b>{{ arg.timeText }}</b> &nbsp;&nbsp;
                                                                {{ arg.event.title }}
                                                            </div>
                                                        </template>
                                                    </FullCalendar>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import interviewRepo from '@/repositories/applicants/interview';
import { ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';

export default {
    components:{
        FullCalendar
    },
    setup() {
        const { interviews, getInterviews } = interviewRepo();
        const calendarOptions = ref({
            plugins: [ dayGridPlugin, interactionPlugin ],
            initialView: 'dayGridMonth',
            selectable: true,
            events: []
        });

        const handleDateClick = (e) => {
            console.log(e);
        }

        onMounted( async () => {
            await getInterviews();
            calendarOptions.value.events = interviews.value;
        });

        return {
            calendarOptions,
            handleDateClick,
            interviews,
            getInterviews
        }
    },
}
</script>

<style>
.fc .fc-daygrid-dot-event {
    background-color: #4FC9DA !important;
    color: #fff !important;
}
.pl-2 {
    padding-left: 10px;
}
</style>