<template>
    <div>
        <form action="/calculate" method="post" v-on:submit.prevent="submitComment">
            <div class="form-group row">
                <label for="start" class="col-md-4 col-form-label text-md-right">Start Date</label>

                <div class="col-md-4">
                    <input type="date" id="start" class="form-control" name="start" value="" v-model="form.start">
<!--                    <p>Hello</p>-->

                </div>
            </div>


            <br/>
            <div class="form-group row">
                <label for="end" class="col-md-4 col-form-label text-md-right">End Date</label>

                <div class="col-md-4">
                    <input type="date" id="end" class="form-control" name="end"  v-model="form.end">
<!--                    <p>Hello</p>-->


                </div>
            </div>


            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Calculate Weekends.
                    </button>

                </div>
            </div>
        </form>
        <br/><br/><br/>

        <div id="resultContainer" v-if="txtDownloadLink !== ''">
            <div class="col-md-6" style="margin: 0 auto; text-align: center;">
                <h4>There are <span v-text="noOfWeekends">6</span> Weekends In Between </h4>
            </div>
            <div class="col-md-6" style="margin: 0 auto; text-align: center;">
                <a :href="txtDownloadLink" :download="txtDownloadName" class="signin_user"><p class="inline download"><b>Download Weekend TXT File&nbsp;</b></p> <!--<p class="inline">&nbsp;&nbsp;(Sign In Required)</p>--><br/></a>
                <a :href="pdfDownloadLink" :download="pdfDownloadName" class="premium_user"><p class="inline download"><b>Download Weekend PDF File&nbsp;</b></p> <!--<p class="inline">&nbsp;&nbsp;(Premium User Required)</p>--><br/></a>
            </div>
        </div>
    </div>

</template>

<script>

    import axios from "axios";

    export default {
        name: 'weekend',

        props:  [''],

        data() {
            return {
                txtDownloadLink: '',
                txtDownloadName: '',
                pdfDownloadLink: '',
                pdfDownloadName: '',
                noOfWeekends: '',
                form: {
                    start : '',
                    end : ''
                },
            }
        },

        methods: {
            submitComment() {
                axios.post('/calculate', this.form)
                    .then(response => {
                        this.form.start = '';
                        this.form.end = '';
                        this.txtDownloadLink = response.data.weekend_txt;
                        this.pdfDownloadLink = response.data.weekend_pdf;
                        this.txtDownloadName = "Raven Weekend Report.txt";
                        this.pdfDownloadName = "Raven Weekend Report.pdf";
                        this.noOfWeekends = response.data.no_of_weekends;
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                    });
            },

        },
        computed : {
            searchInfo() {
                return 'str';
            }
        },

        created() {
        },
    }
</script>

<style>

</style>
