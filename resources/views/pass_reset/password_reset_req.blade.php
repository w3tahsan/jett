@extends('frontend.master')
@section('content')
<section class="middle">
    <div class="container">
        <div class="row align-items-start justify-content-between">
            <div class="col-xl-6 m-auto col-lg-6 col-md-12 col-sm-12 mfliud">
                <div class="mb-3">
                    <h3>Reset Password Request</h3>
                </div>
                @if (session('send'))
                    <div class="alert alert-success">
                        {{ session('send') }}
                    </div>
                @endif
                <form class="border p-3 rounded" action="{{ route('customer.pass.reset.req.send') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Send Request</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection()
