<div class="pt-20 min-h-screen bg-gradient-to-br from-pink-50 via-blue-50 to-purple-50">
  <div class="flex h-full items-center">
    <main class="w-full max-w-md mx-auto p-6">
      <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="p-4 sm:p-7">
          <div class="text-center">
            <h1 class="block text-2xl font-bold text-gray-800">Reset password</h1>
          </div>

          <div class="mt-5">
            <!-- Form -->
            <form wire:submit.prevent='save'>
              @if (session('error'))
              <div class="mt-2 bg-red-500 text-sm text-white rounded-lg p-4 mb-4" role="alert" tabindex="-1" aria-labelledby="hs-solid-color-danger-label">
                {{ session('error') }}
              </div>
            @endif

              <div class="grid gap-y-4">

                <!-- Password -->
                <div>
                  <label for="password" class="block text-sm mb-2 text-gray-700">
                    Password
                  </label>
                  <div class="relative">
                    <input
                      type="password"
                      id="password"
                      wire:model="password"
                      class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm
                             focus:border-blue-500 focus:ring-blue-500
                             disabled:opacity-50 disabled:pointer-events-none"
                      required
                    >
                    @error('password')
                      <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                        <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                        </svg>
                      </div>
                    @enderror
                  </div>
                  @error('password')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                  <label for="password_confirmation" class="block text-sm mb-2 text-gray-700">
                    Confirm Password
                  </label>
                  <div class="relative">
                    <input
                      type="password"
                      id="password_confirmation"
                      wire:model="password_confirmation"
                      class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm
                             focus:border-blue-500 focus:ring-blue-500
                             disabled:opacity-50 disabled:pointer-events-none"
                      required
                    >

                    @error('password_confirmation')
                      <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                        <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                        </svg>
                      </div>
                    @enderror
                  </div>

                  @error('password_confirmation')
                    <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <button
                  type="submit"
                  class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2
                         text-sm font-semibold rounded-lg
                         bg-blue-600 text-white hover:bg-blue-700
                         disabled:opacity-50 disabled:pointer-events-none"
                >
                  Save password
                </button>

              </div>
            </form>
            <!-- End Form -->
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
