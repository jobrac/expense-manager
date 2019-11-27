@extends('layouts.app')

@section('content')
    <dashboard_template :role_id="{{Auth::user()->roles}}"></dashboard_template>
@endsection