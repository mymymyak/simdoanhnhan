@extends('protected.admin.master')
@section('title', 'Admin Dashboard')
@section('content')

    @if (session()->has('flash_message'))
            <p>{{ session()->get('flash_message') }}</p>
    @endif


    <div class="row">
		<div class="col-md-3">
			<div class="callout callout-info">
				<h4>I am an info callout!</h4>
				<p>Follow the steps to continue to payment.</p>
			</div>
		</div>
		<div class="col-md-3">
			<div class="callout callout-info">
				<h4>I am an info callout!</h4>
				<p>Follow the steps to continue to payment.</p>
			</div>
		</div>
		<div class="col-md-3">
			<div class="callout callout-info">
				<h4>I am an info callout!</h4>
				<p>Follow the steps to continue to payment.</p>
			</div>
		</div>
		<div class="col-md-3">
			<div class="callout callout-info">
				<h4>I am an info callout!</h4>
				<p>Follow the steps to continue to payment.</p>
			</div>
		</div>
	</div>


@endsection