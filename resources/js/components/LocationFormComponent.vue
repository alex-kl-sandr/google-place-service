<template>
    <div>
        <div class="alert alert-success" role="alert" v-show="isSuccessful">
            The location was processed successfully!
        </div>
        <div class=" alert alert-danger" role="alert" v-show="isFailed">
            {{ errorMessage }}
        </div>
        <form @submit.prevent="submitForm">
            <div class="form-row">
                <slot></slot>
                <div class="col-10 mr-3">
                    <div class="position-relative">
                        <input class="form-control form-control-lg"
                            type="text"
                            name="location"
                            placeholder="Location"
                            autocomplete="off"
                            v-model="inputValue"
                            @click="isVisible = false">
                        <ul class="dropdown-menu w-100 m-0 " id="suitable-locations" v-show="isVisible">
                            <li class="dropdown-item" v-for="location in locations" @click.stop="setLocation(location)">{{ location }}</li>
                        </ul>
                    </div>
                </div>
                <button type="submit" class="btn btn-light col" @click="isVisible = false">Submit</button>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data: function() {
            return {
                locations: [],
                inputValue: '',
                isVisible: false,
                isSuccessful: false,
                isFailed: false,
                errorMessage: 'Oops... Something went wrong. Please try again!'
            };
        },
        watch: {
            inputValue: function(val, old) {
                if (val.length >= 3 && Math.abs(val.length - old.length) === 1) {
                    axios.get(`/location/get?query=${val}`)
                        .then(response => {
                            if (response.data) {
                                this.locations = response.data;
                                this.isVisible = true;
                            }
                        })
                        .catch(error => {
                            this.isVisible = false;
                            this.locations = [];
                            console.log(error);
                        });
                }
            }
        },
        methods: {
            setLocation: function (location) {
                this.inputValue = location;
                this.isVisible = false;
            },
            submitForm: function () {
                let params = {
                        location: this.inputValue,
                    },
                    error = 'Please enter valid location. You can use hints if you need.';

                if (this.inputValue && this.inputValue.length > 3) {
                    axios.post('/location/add/', params)
                        .then(response => {
                            if (response.data.error) {
                                this.errorMessage = error;
                                this.isFailed = true;
                            } else {
                                this.isSuccessful = true;
                            }
                            setTimeout(() => {
                                this.isSuccessful = this.isFailed = false
                            }, 5000);
                        })
                        .catch(error => {
                            this.isFailed = true;
                            setTimeout(() => {
                                this.isFailed = false
                            }, 5000);
                            console.log(error);
                        });
                } else {
                    this.errorMessage = error;
                    this.isFailed = true;
                    setTimeout(() => {
                        this.isFailed = false
                    }, 5000);
                }
                this.inputValue = '';
            },
        },
    }
</script>

<style scoped>
    #suitable-locations {
        display: block;
    }
    #suitable-locations .dropdown-item:hover {
        cursor: pointer;
    }
</style>