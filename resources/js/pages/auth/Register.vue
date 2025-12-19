<script setup lang="ts">
import { useBodyClasses } from '@/composables/useBodyClasses';
import { login } from '@/routes';
import registerStore from '@/routes/register';
import { Form, Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const togglePasswordConfirmation = () => {
    showPasswordConfirmation.value = !showPasswordConfirmation.value;
};

useBodyClasses(['authentication-bg', 'authentication-bg-pattern']);

</script>

<template>

    <Head title="Register" />
    <div>

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">

                                <div class="text-center w-75 m-auto">
                                    <div class="auth-brand">
                                        <a href="index.html" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <img src="assets/images/logo-dark.png" alt="" height="22">
                                            </span>
                                        </a>

                                        <a href="index.html" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <img src="assets/images/logo-light.png" alt="" height="22">
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Create your account, it takes less than a minute</p>
                                </div>

                                <Form v-bind="registerStore.store.form()"
                                    :reset-on-success="['password', 'password_confirmation']"
                                    v-slot="{ errors, processing }">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input class="form-control" type="text" id="name" name="name" required autofocus
                                            placeholder="Enter your name" :class="{ 'is-invalid': errors.name }">
                                        <div v-if="errors.name" class="invalid-feedback d-block">
                                            {{ errors.name }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="emailaddress" class="form-label">Email address</label>
                                        <input class="form-control" type="email" id="emailaddress" name="email" required
                                            placeholder="Enter your email" :class="{ 'is-invalid': errors.email }">
                                        <div v-if="errors.email" class="invalid-feedback d-block">
                                            {{ errors.email }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input :type="showPassword ? 'text' : 'password'" id="password"
                                                name="password" class="form-control" placeholder="Enter your password"
                                                :class="{ 'is-invalid': errors.password }">
                                            <div class="input-group-text" @click="togglePassword"
                                                style="cursor: pointer;">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        <div v-if="errors.password" class="invalid-feedback d-block">
                                            {{ errors.password }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <div class="input-group input-group-merge">
                                            <input :type="showPasswordConfirmation ? 'text' : 'password'"
                                                id="password_confirmation" name="password_confirmation"
                                                class="form-control" placeholder="Confirm your password"
                                                :class="{ 'is-invalid': errors.password_confirmation }">
                                            <div class="input-group-text" @click="togglePasswordConfirmation"
                                                style="cursor: pointer;">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        <div v-if="errors.password_confirmation" class="invalid-feedback d-block">
                                            {{ errors.password_confirmation }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkbox-terms">
                                            <label class="form-check-label" for="checkbox-terms">I accept <a href="#"
                                                    class="text-primary">Terms and Conditions</a></label>
                                        </div>
                                    </div>

                                    <div class="text-center d-grid">
                                        <button class="btn btn-primary" type="submit" :disabled="processing">
                                            <span v-if="processing">Creating account...</span>
                                            <span v-else>Sign Up</span>
                                        </button>
                                    </div>
                                </Form>

                                <div class="text-center">
                                    <h5 class="mt-3 text-muted">Sign up using</h5>
                                    <ul class="social-list list-inline mt-3 mb-0">
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);"
                                                class="social-list-item border-primary text-primary"><i
                                                    class="mdi mdi-facebook"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);"
                                                class="social-list-item border-danger text-danger"><i
                                                    class="mdi mdi-google"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);"
                                                class="social-list-item border-info text-info"><i
                                                    class="mdi mdi-twitter"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);"
                                                class="social-list-item border-secondary text-secondary"><i
                                                    class="mdi mdi-github"></i></a>
                                        </li>
                                    </ul>
                                </div>

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-white-50">Already have account? <Link :href="login().url"
                                        class="text-white ms-1"><b>Sign In</b></Link>
                                </p>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->


        <footer class="footer footer-alt">
            2015 - {{ new Date().getFullYear() }} © UBold theme by <a href="" class="text-white-50">Coderthemes</a>
        </footer>
    </div>
</template>
