@extends('layouts.app')

@section('content')
    <users_template :role_id="{{Auth::user()->roles}}"></users_template>
@endsection