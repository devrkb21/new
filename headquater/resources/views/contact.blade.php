<x-marketing-layout>
    <div class="bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    {{ __('messages.contact_page_title') }}
                </h2>
                <p class="mt-2 text-lg leading-8 text-gray-600">
                    {{ __('messages.contact_page_subtitle') }}
                </p>
            </div>

            <form action="{{ route('contact.send') }}" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
                @csrf
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <label for="name"
                            class="block text-sm font-semibold leading-6 text-gray-900">{{ __('messages.full_name') }}</label>
                        <div class="mt-2.5">
                            <input type="text" name="name" id="name" autocomplete="name" required
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="email"
                            class="block text-sm font-semibold leading-6 text-gray-900">{{ __('messages.email_address') }}</label>
                        <div class="mt-2.5">
                            <input type="email" name="email" id="email" autocomplete="email" required
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message"
                            class="block text-sm font-semibold leading-6 text-gray-900">{{ __('messages.message') }}</label>
                        <div class="mt-2.5">
                            <textarea name="message" id="message" rows="4" required
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <button type="submit"
                        class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('messages.send_message') }}</button>
                </div>
                @if (session('success'))
                    <div class="mt-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.06 0l4-5.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
            <div class="mt-24 border-t border-gray-200 pt-16">
                <h3 class="text-lg font-medium text-gray-900 text-center">Or connect with us directly</h3>
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">

                    <a href="tel:+8801751126322"
                        class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-100 transition-colors">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-base text-gray-600 hover:text-gray-900 font-medium">+880 1751 126322</p>
                    </a>

                    <a href="mailto:support@coderzonebd.com"
                        class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-100 transition-colors">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <p class="mt-4 text-base text-gray-600 hover:text-gray-900 font-medium">support@coderzonebd.com</p>
                    </a>

                    <a href="https://chat.whatsapp.com/CYgv8YvXRJI4lGXGkm6TVz" target="_blank"
                        class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-100 transition-colors">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <svg class="h-7 w-7" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.06 21.94L7.31 20.58C8.75 21.36 10.36 21.81 12.04 21.81C17.5 21.81 21.95 17.36 21.95 11.91C21.95 6.45 17.5 2 12.04 2M12.04 20.13C10.56 20.13 9.12 19.74 7.84 19L7.54 18.82L3.68 19.94L4.83 16.19L4.64 15.87C3.78 14.59 3.32 13.13 3.32 11.64C3.32 7.15 7.23 3.24 11.77 3.24C16.31 3.24 20.22 7.15 20.22 11.64C20.22 16.14 16.31 20.13 11.77 20.13H12.04M17.13 14.49C16.86 14.36 15.54 13.73 15.31 13.64C15.08 13.55 14.92 13.51 14.76 13.78C14.6 14.05 14.11 14.64 13.95 14.81C13.79 14.98 13.64 15 13.37 14.87C13.1 14.74 12.09 14.39 10.93 13.37C10.02 12.58 9.41 11.63 9.25 11.36C9.09 11.09 9.22 10.95 9.35 10.82C9.46 10.71 9.61 10.5 9.77 10.3C9.93 10.1 10 9.94 10.14 9.68C10.27 9.41 10.19 9.18 10.09 9.05C9.99 8.92 9.41 7.46 9.18 6.91C8.95 6.36 8.71 6.45 8.55 6.45C8.39 6.45 8.23 6.45 8.07 6.45C7.91 6.45 7.62 6.54 7.39 6.81C7.16 7.08 6.62 7.59 6.62 8.7C6.62 9.81 7.43 10.88 7.56 11.05C7.69 11.22 9.25 13.65 11.69 14.64C12.35 14.91 12.83 15.05 13.2 15.14C13.79 15.27 14.34 15.23 14.76 15.1C15.23 14.95 16.48 14.28 16.75 13.6C17.02 12.92 17.02 12.33 16.94 12.19C16.86 12.05 16.69 11.96 16.43 11.83C16.16 11.7 17.4 14.62 17.13 14.49Z" />
                            </svg>
                        </div>
                        <p class="mt-4 text-base text-gray-600 hover:text-gray-900 font-medium">Join our WhatsApp Community</p>
                    </a>

                    <a href="#" target="_blank"
                        class="flex flex-col items-center p-4 rounded-lg hover:bg-gray-100 transition-colors">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <svg class="h-6 w-6" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="mt-4 text-base text-gray-600 hover:text-gray-900 font-medium">Facebook</p>
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-marketing-layout>