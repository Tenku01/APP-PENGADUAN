<x-guest-layout>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>

    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img class="w-20 h-20 " src="{{ asset('img/SambatLur.svg')}} " alt="Logo">
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- NIK -->
            <div>
                <x-label for="nik" :value="('NIK')" />

                <x-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik')" required autofocus />
            </div>

            <!-- Name -->
            <div class="mt-4">
                <x-label for="name" :value="('Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />

                <!-- Pemberitahuan email sudah terpakai -->
                <span id="email-error" class="text-red-500 text-sm"></span>
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <x-label for="phone" :value="('No. HP')" />

                <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="off" /> <!-- Menambahkan autocomplete="off" -->

                <!-- Peringatan untuk password -->
                <span id="password-warning" class="text-sm text-red-500"></span>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="('Konfirmasi Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-3 bg-blue-500 text-white font-bold rounded-md my-3 py-3 px-4 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:bg-blue-500 hover:scale-105 duration-300 ease-in-out">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>

    <!-- Script untuk pengecekan email menggunakan AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            
            emailInput.addEventListener('keyup', function () {
                const email = emailInput.value;
                
                // Pastikan email tidak kosong sebelum melakukan pengecekan
                if (email.length > 0) {
                    fetch(`{{ route('check-email') }}?email=${email}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                emailError.textContent = 'Email sudah digunakan.';
                                emailError.style.color = 'red';
                            } else {
                                emailError.textContent = ''; // Menghapus pesan error jika email belum terpakai
                            }
                        });
                } else {
                    emailError.textContent = ''; // Menghapus pesan error jika email kosong
                }
            });
            
            const passwordInput = document.getElementById('password');
            const passwordWarning = document.getElementById('password-warning');
            
            passwordInput.addEventListener('keyup', function () {
                const password = passwordInput.value;

                // Cek apakah password memiliki minimal 8 karakter dan setidaknya satu huruf besar
                const regex = /^(?=.*[A-Z]).{8,}$/;
                
                if (password.length > 0 && !regex.test(password)) {
                    passwordWarning.textContent = 'Password harus memiliki minimal 8 karakter dan mengandung setidaknya satu huruf besar.';
                } else {
                    passwordWarning.textContent = ''; // Hapus pesan peringatan jika valid
                }
            });
        });
    </script>
</x-guest-layout>
