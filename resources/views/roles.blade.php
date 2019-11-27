@extends('layouts.app')

@section('content')
    <roles_template :role_id="{{Auth::user()->roles}}"></roles_template>
@endsection