@extends('layouts.app')

@section('content')
    <category_template :role_id="{{Auth::user()->roles}}"></category_template>
@endsection