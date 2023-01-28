@extends('layouts.dashboard')
@section('content')
<div class="page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Coupon</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3>Coupon List</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>SL</th>
                        <th>Coupon</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Validity</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($coupons as $sl=>$coupon)
                    <tr>
                        <td>{{ $sl+1 }}</td>
                        <td>{{ $coupon->coupon_code }}</td>
                        <td>{{ ($coupon->type==1?'Percentage':'Solid') }}</td>
                        <td>{{ $coupon->amount }}</td>
                        <td><div class="badge badge-primary">{{ Carbon\Carbon::now()->diffInDays($coupon->validity, false); }} days left</div></td>
                        <td>
                            <a href="" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @can('add_coupon')
        <div class="card">
            <div class="card-header">
                <h3>Add Coupon</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('coupon.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Coupon Code</label>
                        <input type="text" name="coupon_code" class="form-control">
                    </div>
                    <div class="mb-3">
                        <select name="type" class="form-control">
                            <option value=""> -- Select Type -- </option>
                            <option value="1">Percentage</option>
                            <option value="2">Solid Amount</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Discount Amount</label>
                        <input type="number" name="amount" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Validity</label>
                        <input type="date" name="validity" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Add coupon</button>
                    </div>
                </form>
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection
