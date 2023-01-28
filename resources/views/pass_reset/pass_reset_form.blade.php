@extends('frontend.master')

@section('content')
<section class="middle">
    <div class="container">
        <div class="row align-items-start justify-content-between">
            <div class="col-xl-6 m-auto col-lg-6 col-md-12 col-sm-12 mfliud">
                <div class="mb-3">
                    <h3>Reset Password Form</h3>
                </div>
                <form class="border p-3 rounded" action="{{ route('customer.pass.reset') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control">
                            <input type="hidden" name="token" class="form-control" value="{{ $token }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Reset Password</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection
