<x-app-layout>

    @section('title', 'Budgetify | Edit Profile')

    @section('content')
        <div class="d-flex justify-content-center pt-3">
            <h1 class="rainbow_text_animated" style="font-weight: bolder; padding: 10px">
                Edit Profile
            </h1>
        </div>

        <div class="container my-2">
            <div class="row">
                <div class="col">
                    <button class="btn btn-info updatePassword" style="float: right">
                        Update Password
                    </button>
                </div>
            </div>
        </div>

        <div class="container">
            <form id="addUserInfo" enctype="multipart/form-data" method="POST" action="{{ route('userInfo.store') }}">
                @csrf
                <input type="hidden" id="user_id" name="user_id" value="{{ session('user_id') }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label text-white">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control"
                            placeholder="First Name">
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label text-white">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last Name">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <label for="email" class="form-label text-white">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="Enter your email" value="{{ $user->email }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="birth_date" class="form-label text-white">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label text-white">Gender</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="None">Not Specified</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label text-white">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control"
                            placeholder="Phone Number">
                    </div>
                    <div class="col-md-6">
                        <label for="profile_pic" class="form-label text-white">Profile Picture</label>
                        <input type="file" id="profile_pic" name="profile_pic" class="form-control">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="address" class="form-label text-white">Address</label>
                        <textarea name="address" id="address" cols="30" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="bio" class="form-label text-white">Profile Bio</label>
                        <textarea name="bio" id="bio" cols="30" rows="3" class="form-control"
                            placeholder="Write something about yourself..."></textarea>
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>

        <div class="modal fade" tabindex="-1" id="update-password-form">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updatePasswordForm" method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="update_password_current_password" class="form-label">Current Password</label>
                                <input id="update_password_current_password" name="current_password" type="password"
                                    class="form-control" autocomplete="current-password" required>
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <label for="update_password_password" class="form-label">New Password</label>
                                <input id="update_password_password" name="password" type="password"
                                    class="form-control" autocomplete="new-password" required>
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div class="mb-3">
                                <label for="update_password_password_confirmation" class="form-label">Confirm
                                    Password</label>
                                <input id="update_password_password_confirmation" name="password_confirmation"
                                    type="password" class="form-control" autocomplete="new-password" required>
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            @if (session('status') === 'password-updated')
                                <div class="alert alert-success" role="alert">
                                    Password updated successfully.
                                </div>
                            @endif

                            <div class="modal-footer" style="padding-bottom: 0px;">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $('.updatePassword').click(function() {
                $('#update-password-form').modal('show');
            });
        </script>
    @endpush
</x-app-layout>
